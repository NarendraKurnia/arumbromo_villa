<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomSeasonalPrice extends Model
{
    protected $table = 'room_seasonal_prices';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel room_seasonal_prices
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}