<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\MidtransService;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan milik user yang sedang login.
     */
    public function index()
    {
        // PENTING: Jangan gunakan Order::all() !
        // Kita hanya mengambil order milik user yg sedang login menggunakan relasi hasMany.
        // auth()->user()->orders() akan otomatis memfilter: WHERE user_id = current_user_id
        $orders = auth()->user()->orders()
            ->with(['items.product']) // Eager Load nested: Order -> OrderItems -> Product
            ->latest() // Urutkan dari pesanan terbaru
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Order $order, MidtransService $midtrans)
{
    // CEK AKSES USER
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
    }

    // LOAD RELASI
    $order->load(['items.product', 'user']);

    // HITUNG SUBTOTAL PRODUK
    $subtotal = $order->items->sum('subtotal');


    // ONGKOS KIRIM
    $ongkir = $order->shipping_cost ?? 10000;

    // TOTAL BAYAR (INI YANG SEBELUMNYA SALAH)
    $totalBayar = $subtotal + $ongkir;

    // MIDTRANS
    $snapToken = null;
    if ($order->status === 'pending' && is_null($order->snap_token)) {
        $snapToken = $midtrans->createSnapToken($order);
    }

    return view('orders.show', compact(
        'order',
        'subtotal',
        'ongkir',
        'totalBayar',
        'snapToken'
    ));
}

    /**
     * Menampilkan halaman status pembayaran sukses.
     */
    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }
        return view('orders.success', compact('order'));
    }

    /**
     * Menampilkan halaman status pembayaran pending.
     */
    public function pending(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }
        return view('orders.pending', compact('order'));
    }
}