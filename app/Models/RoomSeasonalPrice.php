<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomSeasonalPrice extends Model
{
    protected $table = 'room_seasonal_prices';

    protected $fillable = [
        'room_id',
        'season_name',
        'start_date',
        'end_date',
        'price_per_night',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_per_night' => 'decimal:2',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}