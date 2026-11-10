<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel facilities
    ];

    public function villas()
    {
        return $this->belongsToMany(
            Villa::class,
            'villa_facilities',
            'facility_id',
            'villa_id'
        );
    }
}