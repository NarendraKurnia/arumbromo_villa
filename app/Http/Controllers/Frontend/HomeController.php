<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Facility;

class HomeController extends Controller
{
    public function index()
    {
        $villas = Villa::with([
            'images',
            'rooms'
        ])
        ->where('status', 'active')
        ->latest()
        ->get();

        $facilities = Facility::all();

        return view('frontend.home', compact(
            'villas',
            'facilities'
        ));
    }
}