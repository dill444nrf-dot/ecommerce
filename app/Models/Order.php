<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'shipping_cost',
        'status',
        'shipping',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

     public function items()
    {
        return $this->belongsTo(OrderItem::class);
    }
}