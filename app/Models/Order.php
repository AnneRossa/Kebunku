<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['total_price', 'order_number', 'status', 'session_id', 'user_address_id', 'created_by', 'updated_by'];

    use HasFactory;
    // function order_items() {
    //     return $this->hasMany(OrderItem::class);
    // }
    public function order_items()
    {
        return $this->hasMany(OrderItem::class); // Relasi one-to-many dengan OrderItem
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_address_id');
    }
    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }
}
