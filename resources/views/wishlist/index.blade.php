@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Wishlist Saya</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Jika wishlist kosong --}}
    @if($wishlists->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-heart"></i><br>
            Wishlist kamu masih kosong
        </div>
    @else
        <div class="row g-4">
            @foreach($wishlists as $item)
                <div class="col-6 col-md-4 col-lg-3 position-relative">

                    {{-- Tombol hapus --}}
                    <form action="{{ route('wishlist.destroy', $item->id) }}"
                          method="POST"
                          class="position-absolute top-0 end-0 m-2 z-3">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm rounded-circle">
                            <i class="bi bi-x"></i>
                        </button>
                    </form>

                    {{-- Card produk --}}
                    @include('partials.product-card', [
                        'product' => $item->product
                    ])
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection