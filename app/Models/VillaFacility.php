<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillaFacility extends Model
{
    protected $table = 'villa_facilities';

    public $timestamps = false;

    protected $fillable = [
        'villa_id',
        'facility_id',
    ];
}