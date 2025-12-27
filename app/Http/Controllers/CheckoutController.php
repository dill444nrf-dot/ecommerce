<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CheckoutController extends Controller
{
    /**
     * Halaman checkout
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->cart || $user->cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        $cartItems = $user->cart->items;

        // Perbaiki logika subtotal → pastikan dikalikan quantity
        $subtotal = $cartItems->sum(fn ($item) =>
            ($item->product?->price ?? 0) * $item->quantity
        );

        $shippingCost = 10000; // contoh ongkir

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'shippingCost'
        ));
    }

    /**
     * Proses checkout → simpan order
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->cart || $user->cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'notes'            => 'nullable|string|max:255',
        ]);

        $cartItems = $user->cart->items;

       $subtotal = $cartItems->sum(fn ($item) =>
    $item->price * $item->quantity
);



        $shippingCost = 10000;

        // Buat order
        $order = Order::create([
            'user_id'          => $user->id,
            'order_number'     => 'ORD-' . time(),
            'total_amount'     => $subtotal + $shippingCost,
            'shipping_cost'    => $shippingCost,
            'status'           => 'pending',
            'shipping_name'    => $request->shipping_name,
            'shipping_phone'   => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'notes'            => $request->notes,
        ]);

        // Simpan item order
        foreach ($cartItems as $item) {
            if (!$item->product) continue; // skip jika produk hilang
            $order->items()->create([
                'product_id'   => $item->product_id,
                'product_name' => $item->product->name,
                'price'        => $item->product->price,
                'quantity'     => $item->quantity,
                'subtotal'     => $item->product->price * $item->quantity,
            ]);
        }

        // Kosongkan cart
        $user->cart->items()->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat 🎉');
    }
}