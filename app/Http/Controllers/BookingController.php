<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\Voucher;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingDetail;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $request->validate([
        'room_id' => 'required|integer|exists:rooms,id',
        'check_in' => 'nullable|date',
        'check_out' => 'nullable|date|after:check_in',
        'dewasa' => 'nullable|integer|min:1',
        'anak' => 'nullable|integer|min:0',
    ]);

    $user = session('id_user')
        ? User::find(session('id_user'))
        : null;

    $room = Room::with([
        'images',
        'seasonalPrices' => function ($query) {
            $query->where('status', 'active');
        }
    ])
    ->where('status', 'active')
    ->findOrFail($request->room_id);

    $checkIn = $request->check_in;

    if (!$checkIn) {
        $checkIn = Carbon::tomorrow()->toDateString();
    }

    $checkOut = $request->check_out;

    if (!$checkOut) {
        $checkOut = Carbon::parse($checkIn)
            ->addDay()
            ->toDateString();
    }
    $vouchers = Voucher::withCount([
    'redemptions as user_usage_count' => function ($query) use ($user) {
        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->whereRaw('1 = 0');
        }
    }
])
->where('status', 'active')
->where('start_date', '<=', now())
->where('end_date', '>=', now())
->where(function ($query) {
    $query->whereNull('usage_limit')
          ->orWhereColumn('used_count', '<', 'usage_limit');
})
->orderBy('discount_value', 'desc')
->get();

    return view('booking.index', [
        'title' => 'Pemesanan Kamar - Arum Bromo Villas',
        'user' => $user,
        'room' => $room,
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'dewasa' => $request->dewasa ?? 1,
        'anak' => $request->anak ?? 0,
        'vouchers' => $vouchers,
    ]);
}

    /**
     * Membuat booking baru.
     */
    /**
 * Membuat booking baru.
 */
public function store(Request $request)
{
    // 1. Memastikan pengguna sudah login
    $userId = session('id_user');

    if (!$userId) {
        return redirect()->route('login')
            ->with('error', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
    }

    // 2. Memastikan akun pengguna masih aktif
    $user = User::find($userId);

    if (!$user || $user->status !== 'active') {
        session()->forget(['id_user', 'username', 'nama', 'role']);

        return redirect()->route('login')
            ->with('error', 'Akun kamu tidak aktif. Silakan hubungi administrator.');
    }

    // 3. Validasi input
    $validated = $request->validate([
        'room_id' => 'required|integer|exists:rooms,id',
        'check_in' => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'dewasa' => 'required|integer|min:1',
        'anak' => 'nullable|integer|min:0',
        'quantity' => 'required|integer|min:1',
        'voucher_id' => 'nullable|integer|exists:vouchers,id',
    ]);

    $checkIn = Carbon::parse($validated['check_in']);
    $checkOut = Carbon::parse($validated['check_out']);

    $dewasa = (int) $validated['dewasa'];
    $anak = (int) ($validated['anak'] ?? 0);
    $quantity = (int) $validated['quantity'];

    $totalGuests = $dewasa + $anak;
    $totalNights = (int) $checkIn->diffInDays($checkOut);

    // Voucher yang dipilih pelanggan
    $voucherId = $validated['voucher_id'] ?? null;

    try {

        $bookingCode = DB::transaction(function () use (
            $validated,
            $userId,
            $checkIn,
            $checkOut,
            $totalGuests,
            $totalNights,
            $quantity,
            $voucherId
        ) {

            // 4. Mengunci data kamar selama transaksi
            $room = DB::table('rooms')
                ->where('id', $validated['room_id'])
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if (!$room) {
                throw new \DomainException(
                    'Kamar tidak ditemukan atau sedang tidak tersedia.'
                );
            }

            // 5. Memeriksa kapasitas kamar
            $capacityPerRoom = (int) $room->capacity
                + (int) $room->max_extra_guests;

            $maxCapacity = $capacityPerRoom * $quantity;

            if ($totalGuests > $maxCapacity) {
                throw new \DomainException(
                    'Jumlah tamu melebihi kapasitas kamar yang dipilih.'
                );
            }

            // 6. Memeriksa ketersediaan kamar
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
                throw new \DomainException(
                    'Maaf, jumlah kamar yang tersedia tidak mencukupi.'
                );
            }

            // 7. Menghitung harga berdasarkan tanggal menginap
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

                if ($seasonalPrice) {
                    $price = (float) $seasonalPrice->price_per_night;
                } else {
                    $price = (float) $room->price_per_night;
                }

                $subtotalPerUnit += $price;
            }

            // Total harga seluruh kamar sebelum diskon
            $subtotal = $subtotalPerUnit * $quantity;

            // Harga rata-rata per malam untuk satu kamar
            $averagePrice = round(
                $subtotalPerUnit / $totalNights,
                2
            );

            // ==========================================
            // 8. VALIDASI DAN PERHITUNGAN VOUCHER
            // ==========================================

            $voucher = null;
            $voucherCode = null;
            $discountAmount = 0;

            if ($voucherId) {

                // Mengunci voucher selama proses transaksi
                $voucher = DB::table('vouchers')
                    ->where('id', $voucherId)
                    ->lockForUpdate()
                    ->first();

                if (!$voucher) {
                    throw new \DomainException(
                        'Voucher tidak ditemukan.'
                    );
                }

                // Memeriksa status voucher
                if ($voucher->status !== 'active') {
                    throw new \DomainException(
                        'Voucher sudah tidak aktif.'
                    );
                }

                // Memeriksa masa berlaku
                if (
                    Carbon::parse($voucher->start_date)->gt(now()) ||
                    Carbon::parse($voucher->end_date)->lt(now())
                ) {
                    throw new \DomainException(
                        'Voucher belum berlaku atau sudah kedaluwarsa.'
                    );
                }

                // Memeriksa minimal transaksi
                if ($subtotal < (float) $voucher->min_booking_amount) {
                    throw new \DomainException(
                        'Minimal transaksi untuk menggunakan voucher adalah Rp' .
                        number_format(
                            (float) $voucher->min_booking_amount,
                            0,
                            ',',
                            '.'
                        ) . '.'
                    );
                }

                // Memeriksa batas penggunaan keseluruhan
                if (
                    $voucher->usage_limit !== null &&
                    (int) $voucher->used_count >= (int) $voucher->usage_limit
                ) {
                    throw new \DomainException(
                        'Kuota penggunaan voucher sudah habis.'
                    );
                }

                // Memeriksa batas penggunaan per pengguna
                if ($voucher->usage_per_user !== null) {

                    $userUsage = DB::table('voucher_redemptions')
                        ->where('voucher_id', $voucher->id)
                        ->where('user_id', $userId)
                        ->count();

                    if ($userUsage >= (int) $voucher->usage_per_user) {
                        throw new \DomainException(
                            'Kamu sudah mencapai batas penggunaan voucher ini.'
                        );
                    }
                }

                // Menghitung diskon
                if ($voucher->discount_type === 'percentage') {

                    $discountAmount = $subtotal *
                        ((float) $voucher->discount_value / 100);

                    // Memeriksa batas maksimal diskon
                    if ($voucher->max_discount !== null) {
                        $discountAmount = min(
                            $discountAmount,
                            (float) $voucher->max_discount
                        );
                    }

                } elseif ($voucher->discount_type === 'fixed') {

                    $discountAmount = (float) $voucher->discount_value;

                }

                // Diskon tidak boleh melebihi subtotal
                $discountAmount = min($discountAmount, $subtotal);

                // Pembulatan nominal diskon
                $discountAmount = round($discountAmount, 2);

                $voucherCode = $voucher->code;
            }

            // ==========================================
            // 9. MENGHITUNG TOTAL PEMBAYARAN
            // ==========================================

            $totalAmount = max(
                0,
                round($subtotal - $discountAmount, 2)
            );

            // 10. Membuat kode booking
            do {
                $code = 'ARV-' . now()->format('Ymd') . '-'
                    . strtoupper(Str::random(6));

            } while (
                DB::table('bookings')
                    ->where('booking_code', $code)
                    ->exists()
            );

            // 11. Menyimpan data booking
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $userId,
                'booking_code' => $code,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'total_guests' => $totalGuests,
                'total_nights' => $totalNights,

                'subtotal_amount' => $subtotal,

                'voucher_id' => $voucher ? $voucher->id : null,
                'voucher_code' => $voucherCode,
                'discount_amount' => $discountAmount,

                'total_amount' => $totalAmount,

                'status' => 'pending',
                'notes' => null,
                'expires_at' => now()->addMinutes(30),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 12. Menyimpan detail booking
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

            return $code;

        }, 3);

        // 13. Mengarahkan pengguna ke halaman detail booking
        return redirect()
            ->route('booking.detail', $bookingCode)
            ->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');

    } catch (\DomainException $e) {

        return back()
            ->withInput()
            ->with('error', $e->getMessage());

    } catch (\Throwable $e) {

        report($e);

        return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat membuat booking. Silakan coba kembali.');
    }
}

    /**
     * Menampilkan detail booking.
     */
    /**
 * Menampilkan detail booking.
 */
public function detail($bookingCode)
{
    $userId = session('id_user');

    if (!$userId) {
        return redirect()->route('login');
    }

    $user = User::find($userId);

    if (!$user || $user->status !== 'active') {
        session()->forget(['id_user', 'username', 'nama', 'role']);

        return redirect()->route('login')
            ->with('error', 'Akun tidak aktif.');
    }

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

    // Mengubah tanggal booking menjadi objek Carbon
    $checkIn = Carbon::parse($booking->check_in);
    $checkOut = Carbon::parse($booking->check_out);

    return view('booking.detail', [
        'title' => 'Detail Booking - Arum Bromo Villas',
        'user' => $user,
        'booking' => $booking,
        'bookingDetails' => $bookingDetails,
        'checkIn' => $checkIn,
        'checkOut' => $checkOut,
    ]);
}
}