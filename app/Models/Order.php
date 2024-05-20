<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'products_price',
        'driver_id',
        'taken_at',
        'delivered_at',
    ];

    protected $with = ['orderProducts'];

    public function client()
    {
      return $this->belongsTo(User::class,'client_id');
    }

    public function driver()
    {
      return $this->belongsTo(User::class,'driver_id');
    }

    public function orderProducts()
    {
      return $this->hasMany(OrderProduct::class);
    }

}
