<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachmentRef extends Model
{
    use HasFactory;

    protected $fillable = ['id_dyo', 'pathname'];
}
