<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    protected $table = 'review';

    protected $fillable = [

    ];

    public function villas()
    {
        return $this->belongsToMany(
            Villa::class,
            'nama',
            'keterangan',
            'rating'
        );
    }
}
