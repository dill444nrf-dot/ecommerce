<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    /**
     * Halaman wishlist user
     */
    public function index()
    {
        $wishlists = Wishlist::with('product.primaryImage')
            ->where('user_id', Auth::id())
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle wishlist (AJAX)
     * Tambah / Hapus dari icon ❤️
     */
    public function toggle(Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        // JIKA SUDAH ADA → HAPUS
        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => 'removed'
            ]);
        }

        // JIKA BELUM → TAMBAH
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return response()->json([
            'status' => 'added'
        ]);
    }

    /**
     * Hapus dari halaman wishlist
     */
    public function destroy($id)
    {
        Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Produk dihapus dari wishlist');
    }
}