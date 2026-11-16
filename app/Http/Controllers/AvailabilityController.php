<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input pencarian
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'dewasa' => 'required|integer|min:1',
            'anak' => 'nullable|integer|min:0',
        ]);

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);

        $dewasa = (int) $validated['dewasa'];
        $anak = (int) ($validated['anak'] ?? 0);
        $totalGuests = $dewasa + $anak;

        $totalNights = $checkIn->diffInDays($checkOut);

        // Informasi user berdasarkan session
        $user = session('id_user')
            ? User::find(session('id_user'))
            : null;

        // Ambil kamar aktif
        $rooms = Room::with('images')
            ->where('status', 'active')
            ->get();

        $availableRooms = [];

        foreach ($rooms as $room) {

            // Periksa kapasitas kamar
            $maxCapacity = $room->capacity + $room->max_extra_guests;

            if ($totalGuests > $maxCapacity) {
                continue;
            }

            // Hitung kamar yang sudah dipesan
            $bookedUnits = DB::table('booking_details')
                ->join(
                    'bookings',
                    'bookings.id',
                    '=',
                    'booking_details.booking_id'
                )
                ->where('booking_details.room_id', $room->id)
                ->whereIn('bookings.status', [
                    'pending',
                    'confirmed',
                    'checked_in'
                ])
                ->where(function ($query) {
                    $query->where('bookings.status', '!=', 'pending')
                        ->orWhereNull('bookings.expires_at')
                        ->orWhere('bookings.expires_at', '>', now());
                })
                ->where('bookings.check_in', '<', $checkOut->toDateString())
                ->where('bookings.check_out', '>', $checkIn->toDateString())
                ->sum('booking_details.quantity');

            // Hitung unit tersedia
            $availableUnits = max(
                0,
                $room->total_units - $bookedUnits
            );

            if ($availableUnits <= 0) {
                continue;
            }

            // Hitung harga setiap malam
            $nightlyPrices = [];
            $subtotal = 0;

            for (
                $date = $checkIn->copy();
                $date->lt($checkOut);
                $date->addDay()
            ) {

                $seasonalPrice = DB::table('room_seasonal_prices')
                    ->where('room_id', $room->id)
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', $date->toDateString())
                    ->whereDate('end_date', '>=', $date->toDateString())
                    ->orderByDesc('start_date')
                    ->first();

                $price = $seasonalPrice
                    ? (float) $seasonalPrice->price_per_night
                    : (float) $room->price_per_night;

                $subtotal += $price;

                $nightlyPrices[] = [
                    'date' => $date->format('d M Y'),
                    'price' => $price,
                    'season' => $seasonalPrice
                        ? $seasonalPrice->season_name
                        : 'Normal',
                ];
            }

            $availableRooms[] = [
                'room' => $room,
                'available_units' => $availableUnits,
                'nightly_prices' => $nightlyPrices,
                'total_price' => $subtotal,
                'total_nights' => $totalNights,
            ];
        }

        return view('room.availability', [
            'title' => 'Ketersediaan Kamar - Arum Bromo Villas',
            'user' => $user,
            'availableRooms' => $availableRooms,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'dewasa' => $dewasa,
            'anak' => $anak,
            'totalGuests' => $totalGuests,
            'totalNights' => $totalNights,
        ]);
    }
}