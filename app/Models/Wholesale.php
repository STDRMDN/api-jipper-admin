<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Wholesale extends Model
{
    use HasFactory;


    protected $table = 'wholesales';


    protected $fillable = [
        'name',
        'country',
        'phone_number',
        'email',
        'status',
    ];

    public $timestamps = true;
}
