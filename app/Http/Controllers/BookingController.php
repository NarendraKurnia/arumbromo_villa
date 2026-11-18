<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingDetail;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $user = session('id_user')
        ? User::find(session('id_user'))
        : null;

    $room = Room::with([
            'images',
            'seasonalPrices' => function ($query) {
                $query->where('status', 'active');
            }
        ])->findOrFail($request->room_id);

    $checkIn = $request->check_in;
    $checkOut = $request->check_out;

    // Tanggal default jika pengguna belum memilih tanggal
    if (!$checkIn) {
        $checkIn = Carbon::tomorrow()->toDateString();
    }

    if (!$checkOut) {
        $checkOut = Carbon::parse($checkIn)->addDay()->toDateString();
    }

    return view('booking.index', [
        'title' => 'Pemesanan Kamar - Arum Bromo Villas',
        'user' => $user,
        'room' => $room,
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'dewasa' => $request->dewasa ?? 1,
        'anak' => $request->anak ?? 0,
    ]);
}

    /**
     * Membuat booking baru.
     */
    public function store(Request $request)
    {
        // Pastikan pengguna sudah login
        $userId = session('id_user');

        if (!$userId) {
            return redirect('/login')->with(
                'error',
                'Silakan login terlebih dahulu untuk melakukan pemesanan.'
            );
        }

        // Validasi input
        $validated = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'dewasa' => 'required|integer|min:1',
            'anak' => 'nullable|integer|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);

        $dewasa = (int) $validated['dewasa'];
        $anak = (int) ($validated['anak'] ?? 0);
        $quantity = (int) $validated['quantity'];

        $totalGuests = $dewasa + $anak;
        $totalNights = (int) $checkIn->diffInDays($checkOut);

        try {

            $booking = DB::transaction(function () use (
                $validated,
                $userId,
                $checkIn,
                $checkOut,
                $totalGuests,
                $totalNights,
                $quantity
            ) {

                /*
                 * Kunci baris kamar agar proses booking
                 * untuk tipe kamar yang sama tidak berjalan
                 * bersamaan tanpa pengecekan ulang.
                 */
                $room = DB::table('rooms')
                    ->where('id', $validated['room_id'])
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->first();

                if (!$room) {
                    throw new \Exception('Kamar tidak ditemukan atau tidak aktif.');
                }

                // Periksa kapasitas berdasarkan jumlah unit
                $maxCapacity = (
                    (int) $room->capacity +
                    (int) $room->max_extra_guests
                ) * $quantity;

                if ($totalGuests > $maxCapacity) {
                    throw new \Exception(
                        'Jumlah tamu melebihi kapasitas kamar yang dipilih.'
                    );
                }

                // Hitung jumlah unit yang sudah dipesan
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

                $availableUnits = (int) $room->total_units - (int) $bookedUnits;

                if ($quantity > $availableUnits) {
                    throw new \Exception(
                        'Maaf, jumlah kamar yang tersedia tidak mencukupi.'
                    );
                }

                // Hitung harga setiap malam
                $subtotalPerUnit = 0;

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

                    $subtotalPerUnit += $price;
                }

                // Total harga seluruh unit kamar
                $subtotal = $subtotalPerUnit * $quantity;

                // Harga rata-rata per malam untuk snapshot booking_details
                $averagePrice = round(
                    $subtotalPerUnit / $totalNights,
                    2
                );

                // Buat kode booking
                $bookingCode = 'ARV-' .
                    now()->format('Ymd') . '-' .
                    strtoupper(Str::random(6));

                // Simpan booking
                $bookingId = DB::table('bookings')->insertGetId([
                    'user_id' => $userId,
                    'booking_code' => $bookingCode,
                    'check_in' => $checkIn->toDateString(),
                    'check_out' => $checkOut->toDateString(),
                    'total_guests' => $totalGuests,
                    'total_nights' => $totalNights,
                    'subtotal_amount' => $subtotal,
                    'voucher_id' => null,
                    'voucher_code' => null,
                    'discount_amount' => 0,
                    'total_amount' => $subtotal,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(30),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Simpan detail booking
                DB::table('booking_details')->insert([
                    'booking_id' => $bookingId,
                    'room_id' => $room->id,
                    'room_name' => $room->name,
                    'quantity' => $quantity,
                    'price_per_night' => $averagePrice,
                    'total_nights' => $totalNights,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $bookingCode;
            });

            return redirect()
                ->route('booking.detail', $booking)
                ->with('success', 'Booking berhasil dibuat.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    /**
     * Menampilkan detail booking.
     */
    public function detail($bookingCode)
    {
        $userId = session('id_user');

        if (!$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);

        $booking = DB::table('bookings')
            ->where('booking_code', $bookingCode)
            ->where('user_id', $userId)
            ->first();

        if (!$booking) {
            abort(404);
        }

        $bookingDetails = DB::table('booking_details')
            ->where('booking_id', $booking->id)
            ->get();

        return view('booking.detail', [
            'title' => 'Detail Booking - Arum Bromo Villas',
            'user' => $user,
            'booking' => $booking,
            'bookingDetails' => $bookingDetails,
        ]);
    }
}