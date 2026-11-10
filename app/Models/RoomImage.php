<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $table = 'room_images';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel room_images
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}