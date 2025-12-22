<?php
// app/Observers/ProductObserver.php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class ProductObserver
{
    public function created(Product $product): void
    {
        Cache::forget('featured_products');
        Cache::forget('category_' . $product->category_id . '_products');

        // ✅ GANTI activity() DENGAN LOG
        Log::info('Produk baru dibuat', [
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'name'       => $product->name,
        ]);
    }

    public function updated(Product $product): void
    {
        Cache::forget('product_' . $product->id);
        Cache::forget('featured_products');

        if ($product->isDirty('category_id')) {
            Cache::forget('category_' . $product->getOriginal('category_id') . '_products');
            Cache::forget('category_' . $product->category_id . '_products');
        }

        Log::info('Produk diperbarui', [
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
        ]);
    }


public function store(Request $request)
{
    // ✅ VALIDASI (INI WAJIB)
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'description' => 'required',
        'price'       => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'stock'       => 'required|integer',
        'weight'      => 'required|numeric',

        // 🔥 INI YANG BIKIN NEXT GABISA KALAU SALAH
        'images'      => 'nullable',
        'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // ✅ SIMPAN PRODUK
    $product = Product::create($validated);

    // ✅ HARUS ADA RETURN
    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Produk berhasil ditambahkan');
}


    public function deleted(Product $product): void
    {
        Cache::forget('product_' . $product->id);
        Cache::forget('featured_products');
        Cache::forget('category_' . $product->category_id . '_products');

        Log::info('Produk dihapus', [
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
        ]);
    }
}
