<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Villa;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::with([
            'images',
            'rooms',
            'facilities'
        ])
        ->where('status', 'active')
        ->latest()
        ->paginate(9);

        return view('frontend.villas.index', compact('villas'));
    }

    public function show($slug)
    {
        $villa = Villa::with([
            'images',
            'rooms.images',
            'rooms.seasonalPrices',
            'facilities'
        ])
        ->where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();

        return view('frontend.villas.show', compact('villa'));
    }
}