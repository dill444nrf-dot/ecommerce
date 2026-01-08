@extends('layouts.app')

@section('title', 'Beranda - Modern Steel Blue')

@section('content')

{{-- SWIPER CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --tkp-primary: #3B6181;
        --tkp-primary-dark: #2d4a63;
        --tkp-primary-light: #f0f5f9;
        --text-dark: #1a1d23;
        --text-muted: #64748b;
        --bg-body: #ffffff;
    }

    body {
        background-color: var(--bg-body);
        color: var(--text-dark);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ===== HERO BANNER ===== */
    .hero-section {
        padding-top: 24px;
    }

    .heroSwiper { 
        border-radius: 24px;
        height: 370px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06); 
        } 

        .hero-img { 
        width: 100%;
        height: 100%; 
        object-fit: cover contain;
     }


    /* ===== CATEGORY FULL ROUNDED (EDGE TO EDGE) ===== */
    .category-wrapper {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        text-align: center;
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .category-icon-box {
        width: 110px;
        height: 110px;
        border-radius: 50% !important;
        overflow: hidden;
        border: 3px solid #f1f5f9;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        background: #f8fafc;
    }

    .category-icon-box img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Gambar full membulat tanpa putih */
        transition: transform 0.5s ease;
    }

    .category-wrapper:hover .category-icon-box {
        transform: translateY(-8px);
        border-color: var(--tkp-primary);
        box-shadow: 0 12px 25px rgba(90, 135, 173, 0.377);
    }

    .category-wrapper:hover img {
        transform: scale(1.15);
    }

    .category-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-dark);
        margin-top: 4px;
    }

    /* ===== SECTION TITLES ===== */
    .section-title {
        font-weight: 800;
        font-size: 1.6rem;
        letter-spacing: -0.5px;
    }

    .title-line {
        width: 50px;
        height: 4px;
        background: var(--tkp-primary);
        border-radius: 2px;
        margin: 8px auto 0;
    }

    /* ===== PROMO BANNER WITH IMAGES ===== */
    .promo-card {
        border-radius: 28px;
        border: none;
        overflow: hidden;
        position: relative;
        min-height: 280px;
    }

    .promo-content {
        position: relative;
        z-index: 2;
        max-width: 65%;
    }

    .promo-img-wrapper {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 300px;
        height: auto;
        z-index: 1;
        transition: transform 0.4s ease;
    }
    .promo-img-wrappers{
        position: absolute;
        bottom: 0;
        right: 0;
        width: 200px;
        height: auto;
        z-index: 1;
        transition: transform 0.4s ease;
    }

    .promo-card:hover .promo-img-wrapper {
        transform: scale(1.1) rotate(-5deg);
    }

    .btn-promo {
        background: #ffffff;
        color: var(--text-dark);
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 14px;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        border: none;
    }

    .btn-promo:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        background: #f8fafc;
    }

    /* ===== PRODUCT GRID ===== */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    @media (min-width: 768px) {
        .product-grid { grid-template-columns: repeat(4, 1fr); }
    }

    @media (min-width: 1200px) {
        .product-grid { grid-template-columns: repeat(5, 1fr); }
    }

    .btn-see-all {
        color: var(--tkp-primary);
        font-weight: 700;
        text-decoration: none;
        font-size: 15px;
    }
</style>

<div class="container-fluid px-lg-5">
    
    {{-- 1. HERO BANNER --}}
    <section class="hero-section mb-5">
        <div class="swiper heroSwiper shadow">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="images/new.jpeg" class="hero-img">
                </div>
                <div class="swiper-slide">
                   <a href="{{ route('catalog.index') }}"><img src="images/pict1.jpeg" class="hero-img"></a>
                </div>
                <div class="swiper-slide">
                    <img src="images/bestseller.jpeg" class="hero-img">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    {{-- 2. KATEGORI PILIHAN (BULAT FULL) --}}
    <section class="mb-5 pb-4">
        <div class="text-center mb-5">
            <h4 class="section-title mb-0">Kategori Pilihan</h4>
            <div class="title-line"></div>
        </div>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-5 justify-content-center text-center">
            @foreach($categories as $category)
                <div class="col">
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="category-wrapper">
                        <div class="category-icon-box">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                        </div>
                        <div class="category-name text-truncate w-100">{{ $category->name }}</div>
                        <span class="badge rounded-pill bg-light text-muted fw-normal mt-1">{{ $category->products_count }} Item</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 3. PRODUK UNGGULAN --}}
    <section class="mb-5 py-5" style="background: #fcfdfe; margin: 0 -3rem; padding: 3rem;">
        <div class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h4 class="section-title mb-0">Produk Unggulan</h4>
                    <p class="text-muted small mt-2">Koleksi terbaik yang paling banyak dicari minggu ini.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="btn-see-all">Lihat Semua Katalog <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="product-grid">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. PROMO BANNER DENGAN GAMBAR --}}
    <section class="mb-5 py-4">
        <div class="row g-4">
            {{-- Flash Sale --}}
            <div class="col-md-6">
                <div class="promo-card p-5 h-100 text-white shadow" style="background: linear-gradient(135deg, #FFB700 0%, #FF8000 100%);">
                    <div class="promo-content">
                        <span class="badge bg-white text-dark mb-3 px-3 shadow-sm">Flash Sale</span>
                        <h2 class="fw-bold mb-3">Diskon Seru Hari Ini!</h2>
                        <p class="fs-5 opacity-90">Potongan harga hingga <span class="fw-bold">70%</span> tanpa minimum belanja.</p>
                        <div class="mt-4">
                            <a href="#" class="btn-promo">Belanja Sekarang</a>
                        </div>
                    </div>
                    <img src="images/api.png" class="promo-img-wrappers" alt="Flash Sale Illustration">
                </div>
            </div>

            {{-- Voucher --}}
            <div class="col-md-6">
                <div class="promo-card p-5 h-100 text-white shadow" style="background: linear-gradient(135deg, #3B6181 0%, #5a8bb5 100%);">
                    <div class="promo-content">
                        <span class="badge bg-white text-dark mb-3 px-3 shadow-sm">New User</span>
                        <h2 class="fw-bold mb-3">Voucher Belanja</h2>
                        <p class="fs-5 opacity-90">Gunakan kode promo spesial:<br><strong class="fs-3 text-warning">BARUUNTUNG</strong></p>
                        <div class="mt-4">
                            <a href="{{ route('register') }}" class="btn-promo text-info">Klaim Voucher</a>
                        </div>
                    </div>
                    <img src="images/promo.png" class="promo-img-wrapper" alt="Voucher Illustration">
                </div>
            </div>
        </div>
    </section>

    {{-- 5. PRODUK TERBARU --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h4 class="section-title mb-0">Baru di Katalog</h4>
                <p class="text-muted small mt-2">Update stok terbaru setiap harinya.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn-see-all">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="product-grid">
            @foreach($latestProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
</div>

{{-- SWIPER JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper(".heroSwiper", {
            loop: true,
            speed: 1000,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true, dynamicBullets: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        });
    });
</script>
@endsection