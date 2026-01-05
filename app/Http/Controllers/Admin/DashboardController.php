<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        //1. statistik utama (cards)
        //kita menghitung data real-time dari database
        //konsep nya menggunakan method agregat database (sum,count)drpd menarik data ke php (get()->count)
        //alasannya karna jauh lebih hemat memori server
        
        $stats = [
            'total_revenue' => Order::whereIn('status', ['processing', 'completed'])
            ->sum('total_amount'), 

            'total_orders' => Order::count(),

            //pending orders:yg perlu tindakan segera admin
            'pending_orders' => Order::where('status', 'pending')
                                     ->where('payment_status', 'paid') //sudah bayar tp blm diproses
                                     ->count(),
            
            'total_products' => Product::count(),

            'total_customers' => User::where('role', 'customer')->count(),

            //stok rendah: produk dengan stok <=5
            //brguna utk notifikasi re-stock
            'low_stock' => Product::where('stock', '<=', 5)->count(),
        ];
        //2. data tabel pesanan terbaru (5 transaksi terakhir)
        //eager load 'user' utk menghindari n+1 query problem saat menampilkan nm cust di blade.
        $recentOrders = Order::with('user')
            ->latest() //alias orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        //3. produk terlaris
        //tantangan: menghitung total qty terjual dri tabel relasi (orders_items)
        //solusi: withCount dg query modifikasi (SUM quantity)
        $topProducts = Product::withCount(['orderItems as sold' => function ($q) {
            $q->select(DB::raw('SUM(quantity)'))
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'paid');
            });
        }])
        ->having('sold', '>', 0) //hny menampilkan yg prnh terjual
        ->orderByDesc('sold') //mengurutkan dari yg paling laku
        ->take(5)
        ->get();

        //4. data grafik pendapatan (7 hri terakhir)
        //kasus: grouping data pertanggal
        //kita gunakan DB:raw utk format tgl dri timestamp 'created_at'
        $revenueChart = Order::select([
            DB::raw('DATE(created_at) as date'), //ambil tanggalnya saja (2024-12-10)
            DB::raw('SUM(total_amount) as total') //total omset hari itu
        ])
        ->where('payment_status', 'paid')
        ->where('created_at', '>=', now()->subDays(7)) //filter 7 hari ke belakang
        ->groupBy('date') //kelompokkan baris berdasarkan tanggal
        ->orderBy('date', 'asc') //urutkan kronologis
        ->get();

    return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'revenueChart'));
    }
}