<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forders extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'city',
        'email',
        'your_name',
        'state',
        'phone_number',
        'shipping_address',
        'zip_code',
        'material',
        'attachments',
        'order_list',
        'jersey_material',
        'jersey_size_chart',
        'custom_jersey_size',
        'rush_shipping',
        'status',
    ];

    protected $casts = [
        'attachments' => 'array',
        'rush_shipping' => 'boolean',
    ];
}
