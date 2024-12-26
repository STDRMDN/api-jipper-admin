<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dyo extends Model
{
    use HasFactory;

    protected $fillable = ['team', 'name', 'phone', 'email', 'id_ref', 'description', 'status'];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'id_dyo');
    }

    public function attachmentRefs()
    {
        return $this->hasMany(AttachmentRef::class, 'id_dyo');
    }
}
