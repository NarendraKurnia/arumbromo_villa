<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Villa extends Model
{
    protected $table = 'villas';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel villas
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VillaImage::class, 'villa_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'villa_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(
            Facility::class,
            'villa_facilities',
            'villa_id',
            'facility_id'
        )->withTimestamps();
    }
}