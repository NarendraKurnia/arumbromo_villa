<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel rooms
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class, 'villa_id');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }

    public function seasonalPrices()
    {
        return $this->hasMany(
            RoomSeasonalPrice::class,
            'room_id'
        );
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'room_id');
    }
}