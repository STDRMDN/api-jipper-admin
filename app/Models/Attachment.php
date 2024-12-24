<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['id_dyo', 'pathname'];

    public function dyo()
    {
        return $this->belongsTo(Dyo::class, 'id_dyo');
    }
}
