<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;

class RoomController extends Controller
{
    /**
     * Halaman daftar kamar
     */
    public function index(Request $request)
    {
        $rooms = Room::with(['images'])
            ->where('status', 'active')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $user = session('id_user')
            ? User::find(session('id_user'))
            : null;

        return view('room.index', [
            'title' => 'Pilihan Kamar - Arum Bromo Villas',
            'user'  => $user,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Halaman detail kamar
     */
    public function detail($slug)
    {
        $room = Room::with([
                'images',
                'seasonalPrices'
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $user = session('id_user')
            ? User::find(session('id_user'))
            : null;

        return view('room.detail', [
            'title' => $room->name . ' - Arum Bromo Villas',
            'user'  => $user,
            'room'  => $room,
        ]);
    }
}