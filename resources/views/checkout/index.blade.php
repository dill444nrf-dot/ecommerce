@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h3 class="mb-4 fw-bold">🛒 Checkout</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            Keranjang kosong. <a href="{{ route('catalog.index') }}">Belanja sekarang</a>
        </div>
    @else
        <div class="row">
            {{-- Ringkasan Pesanan --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Ringkasan Pesanan</h5>
                        <ul class="list-group mb-3">
                            @foreach($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $item->product?->name ?? 'Produk dihapus' }} x {{ $item->quantity }}
                                    <span>Rp {{ number_format(($item->product?->price ?? 0) * $item->quantity) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="fw-bold mb-1">Ongkir: Rp {{ number_format($shippingCost) }}</p>
                        <p class="fw-bold mb-0">Total: Rp {{ number_format($subtotal + $shippingCost) }}</p>
                    </div>
                </div>
            </div>

            {{-- Form Checkout --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Data Pengiriman</h5>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="shipping_name" class="form-label">Nama Penerima</label>
                                <input type="text" name="shipping_name" id="shipping_name"
                                       class="form-control @error('shipping_name') is-invalid @enderror"
                                       value="{{ old('shipping_name', auth()->user()->name) }}" required>
                                @error('shipping_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="shipping_phone" class="form-label">No. HP</label>
                                <input type="text" name="shipping_phone" id="shipping_phone"
                                       class="form-control @error('shipping_phone') is-invalid @enderror"
                                       value="{{ old('shipping_phone', auth()->user()->phone) }}" required>
                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Alamat</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3"
                                          class="form-control @error('shipping_address') is-invalid @enderror" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan (opsional)</label>
                                <textarea name="notes" id="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                🛒 Bayar & Buat Pesanan
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection