{{-- ================================================
     FILE: resources/views/partials/product-card.blade.php
     FUNGSI: Card produk + keranjang + wishlist (AJAX)
     ================================================ --}}

<div class="card h-100 shadow-sm border-0 position-relative">

    {{-- Tombol Wishlist --}}
    @auth
        @php
            $isWishlisted = auth()->user()->hasInWishlist($product);
        @endphp

        <button
            type="button"
            class="btn btn-sm rounded-circle wishlist-btn position-absolute top-0 end-0 m-2
            {{ $isWishlisted ? 'btn-danger' : 'btn-outline-danger' }}"
            data-id="{{ $product->id }}"
            style="z-index: 9999;">
            <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </button>
    @else
        <a href="{{ route('login') }}"
           class="btn btn-sm btn-outline-danger rounded-circle position-absolute top-0 end-0 m-2"
           style="z-index: 9999;">
            <i class="bi bi-heart"></i>
        </a>
    @endauth

    {{-- Gambar Produk (TANPA <a>, BIAR KLIK WISHLIST AMAN) --}}
    <img src="{{ optional($product->primaryImage)->url }}"
         alt="{{ $product->name }}"
         class="card-img-top"
         style="height:180px; object-fit:cover;">

    {{-- Body --}}
    <div class="card-body d-flex flex-column">
        <h6 class="card-title mb-1">{{ $product->name }}</h6>

        <small class="text-muted mb-2">
            Rp {{ number_format($product->price) }}
        </small>

        {{-- Tombol Tambah Keranjang --}}
        <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            @if($product->stock > 0)
                <button type="submit" class="btn btn-success btn-sm w-100">
                    <i class="bi bi-cart-plus me-1"></i>
                    Tambah ke Keranjang
                </button>
            @else
                <button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                    Stok Habis
                </button>
            @endif
        </form>

        {{-- Tombol Detail --}}
        <a href=""
           class="btn btn-outline-secondary btn-sm w-100 mt-2">
            Detail
        </a>
    </div>
</div>