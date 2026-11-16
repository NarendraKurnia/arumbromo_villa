<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Villa extends Model
{
    protected $table = 'villas';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'city',
        'province',
        'map_url',
        'check_in_time',
        'check_out_time',
        'status',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VillaImage::class, 'villa_id', 'id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'villa_id', 'id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Facility::class,
            'villa_facilities',
            'villa_id',
            'facility_id'
        )->withTimestamps();
    }
}