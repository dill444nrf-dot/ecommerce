<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'midtrans',
        'payment_type',
        'status',
        'gross_amount',
        'snap_token',
        'raw_response',
    ];

     public function order()
    {
        return $this->belongsTo(Order::class);
    }
}