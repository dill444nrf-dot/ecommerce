<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id'];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item keranjang
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Hitung total jumlah barang
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Hitung total harga dengan diskon
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            // Gunakan harga diskon jika ada
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->quantity;
        });
    }
}