<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Halaman keranjang
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id]
        );

        $cart->load('items.product.category');

        return view('cart.index', compact('cart'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // quantity OPTIONAL (default 1)
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $qty = $request->quantity ?? 1;

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Cek stok
        if ($qty > $product->stock) {
            return back()->with('error', 'Stok tidak cukup');
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Cari item dulu (JANGAN pakai updateOrCreate + DB::raw)
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $qty;

            if ($newQty > $product->stock) {
                return back()->with('error', 'Stok tidak cukup');
            }

            $cartItem->update([
                'quantity' => $newQty
            ]);
        } else {
            CartItem::create([
                'cart_id'   => $cart->id,
                'product_id'=> $product->id,
                'quantity'  => $qty,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update quantity
     */
    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::whereHas('cart', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($cartItemId);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Stok tidak cukup');
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Keranjang diperbarui');
    }

    /**
     * Hapus item
     */
    public function remove($cartItemId)
    {
        $cartItem = CartItem::whereHas('cart', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($cartItemId);

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang');
    }
}