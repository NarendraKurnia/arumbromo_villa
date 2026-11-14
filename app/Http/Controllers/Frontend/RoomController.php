<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    public function show($id)
    {
        $room = Room::with([
            'villa.images',
            'images',
            'seasonalPrices'
        ])
        ->where('status', 'active')
        ->findOrFail($id);

        if ($room->villa->status !== 'active') {
            abort(404);
        }

        return view('frontend.rooms.show', compact('room'));
    }
}