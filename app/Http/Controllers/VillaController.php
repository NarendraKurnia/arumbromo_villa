<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villa;
use App\Models\User;

class VillaController extends Controller
{
    /**
     * Halaman daftar villa
     */
    public function index(Request $request)
    {
        $villas = Villa::with([
                'images',
                'rooms',
                'facilities'
            ])
            ->where('status', 'active')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $user = session('id_user')
            ? User::find(session('id_user'))
            : null;

        return view('villa.index', [
            'title'  => 'Daftar Villa - Arum Bromo Villas',
            'user'   => $user,
            'villas' => $villas,
        ]);
    }


    /**
     * Halaman detail villa
     */
    public function detail($slug)
    {
        $villa = Villa::with([
                'images',
                'facilities',
                'rooms' => function ($query) {
                    $query->where('status', 'active')
                        ->with([
                            'images',
                            'seasonalPrices'
                        ]);
                }
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $user = session('id_user')
            ? User::find(session('id_user'))
            : null;

        return view('villa.detail', [
            'title' => $villa->name . ' - Arum Bromo Villas',
            'user'  => $user,
            'villa' => $villa,
        ]);
    }
}
