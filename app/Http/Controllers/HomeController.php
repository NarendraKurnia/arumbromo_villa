<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Facility;
use App\Models\ReviewModel;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil kamar aktif beserta informasi villa dan fotonya
        $rooms = Room::with(['villa', 'images'])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        // Mengambil fasilitas
        $facilities = Facility::all();

        // Mengambil review terbaru
        $reviews = ReviewModel::latest()
            ->take(6)
            ->get();

        // Mengambil informasi user yang sedang login
        $user = session('id_user')
            ? User::find(session('id_user'))
            : null;

        return view('home.index', [
            'title'      => 'Arum Bromo Villas',
            'user'       => $user,
            'rooms'      => $rooms,
            'facilities' => $facilities,
            'reviews'    => $reviews
        ]);
    }
}