<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class, 'id_order');
    }

    public function payment()
    {
        return $this->hasOne(PaymentApproval::class, 'id_order');
    }

    public function dyo()
    {
        return $this->belongsTo(Dyo::class, 'id_dyo');
    }
}
