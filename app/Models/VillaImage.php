<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillaImage extends Model
{
    protected $table = 'villa_images';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel villa_images
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class, 'villa_id');
    }
}