<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'user_id', 'quantity', 'unit_price'
    ];

    public function product() {
        return $this ->belongsTo(Product::class, 'product_id');
    }
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function user() {
        return $this ->belongsTo(User::class, 'user_id');
    }

}
