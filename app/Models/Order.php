<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'products_price'
    ];

    public function client()
    {
      return $this->belongsTo(User::class,'client_id');
    }

    public function orderProducts()
    {
      return $this->hasMany(OrderProduct::class);
    }

}
