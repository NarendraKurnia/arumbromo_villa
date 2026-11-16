<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'villa_id',
        'name',
        'slug',
        'description',
        'capacity',
        'max_extra_guests',
        'price_per_night',
        'total_units',
        'room_size',
        'status',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class, 'villa_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class, 'room_id', 'id');
    }

    public function seasonalPrices()
    {
        return $this->hasMany(RoomSeasonalPrice::class, 'room_id', 'id');
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'room_id', 'id');
    }
}