<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'status',
        'email',
        'country',
        'name',
        'address',
        'address_detail',
        'city',
        'province',
        'postal_code',
        'phone',
        'shipping_method',
        'subtotal',
        'shipping_fee',
        'tax',
        'total',
        'is_product',
        'id_dyo'
    ];

    protected $guarded = [];

    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class, 'id_order');
    }
}
