<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', 'address1', 'address2', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'isMain', 'user_id', 'nomorTlp', 'name', 'ktp'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
