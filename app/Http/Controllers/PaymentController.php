<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;

use App\Services\MidtransService;

use Carbon\Carbon;
use Exception;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Membuat transaksi pembayaran.
     */
    public function create($bookingCode)
    {
        $userId = session('id_user');

        if (!$userId) {
            return redirect()->route('login');
        }

        $booking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('booking.detail', $bookingCode)
                ->with('error', 'Booking ini tidak dapat dibayar.');
        }

        if (
            $booking->expires_at &&
            Carbon::parse($booking->expires_at)->isPast()
        ) {
            return redirect()
                ->route('booking.detail', $bookingCode)
                ->with('error', 'Waktu pembayaran booking telah berakhir.');
        }

        if ($booking->total_amount <= 0) {
            return back()->with('error', 'Total pembayaran tidak valid.');
        }

        // Cek apakah sudah ada pembayaran berhasil.
        $successfulPayment = Payment::where('booking_id', $booking->id)
            ->where('status', 'verified')
            ->first();

        if ($successfulPayment) {
            return redirect()
                ->route('booking.detail', $bookingCode)
                ->with('info', 'Booking ini sudah dibayar.');
        }

        // Ambil data pelanggan.
        $user = User::findOrFail($userId);

        // Buat ID transaksi unik.
        $paymentCode = 'PAY-' . strtoupper(Str::random(12));

        $orderId = 'ARV-' . $booking->id . '-' . strtoupper(Str::random(8));

        $amount = (int) round((float) $booking->total_amount);

        // Simpan data pembayaran terlebih dahulu.
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway' => 'midtrans',
            'payment_code' => $paymentCode,
            'midtrans_order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        try {

            $params = [
    'transaction_details' => [
        'order_id' => $orderId,
        'gross_amount' => $amount,
    ],

    // Metode pembayaran yang diperbolehkan.
    // Kartu kredit sengaja tidak dimasukkan.
    'enabled_payments' => [
        'gopay',
        'shopeepay',
        'other_qris',

        'bca_va',
        'bni_va',
        'bri_va',
        'permata_va',
        'echannel',
    ],

    'customer_details' => [
        'first_name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone ?? '',
    ],

    'item_details' => [
        [
            'id' => 'BOOKING-' . $booking->id,
            'price' => $amount,
            'quantity' => 1,
            'name' => 'Booking Arum Bromo Villas',
        ],
    ],

    'expiry' => [
        'start_time' => now()->format('Y-m-d H:i:s O'),
        'unit' => 'minute',
        'duration' => 30,
    ],
];

            $transaction = $this->midtrans->createTransaction($params);

            $payment->update([
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
            ]);

            return view('booking.payment', [
                'title' => 'Pembayaran - Arum Bromo Villas',
                'booking' => $booking,
                'payment' => $payment,
                'snapToken' => $transaction->token,
                'clientKey' => config('services.midtrans.client_key'),
                'isProduction' => config('services.midtrans.is_production'),
            ]);

        } catch (Exception $e) {

    $payment->update([
        'admin_note' => 'Gagal membuat transaksi Midtrans.',
    ]);

    Log::error('Midtrans Payment Error', [
        'booking_code' => $bookingCode,
        'payment_code' => $paymentCode,
        'message' => $e->getMessage(),
    ]);

    return redirect()
        ->route('booking.detail', $bookingCode)
        ->with('error', 'Gagal membuat pembayaran. Silakan coba kembali.');
}
    }

    /**
 * Menerima notifikasi pembayaran dari Midtrans.
 */
public function notification(Request $request)
{
    $notification = $request->all();

    // Ambil data notifikasi.
    $orderId = $notification['order_id'] ?? null;
    $statusCode = $notification['status_code'] ?? null;
    $grossAmount = $notification['gross_amount'] ?? null;
    $signatureKey = $notification['signature_key'] ?? null;

    if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
        return response()->json([
            'message' => 'Data notifikasi tidak lengkap.'
        ], 400);
    }

    // Verifikasi signature Midtrans.
    $serverKey = config('services.midtrans.server_key');

    $expectedSignature = hash(
        'sha512',
        $orderId . $statusCode . $grossAmount . $serverKey
    );

    if (!hash_equals($expectedSignature, $signatureKey)) {

        Log::warning('Midtrans invalid signature', [
            'order_id' => $orderId,
        ]);

        return response()->json([
            'message' => 'Invalid signature.'
        ], 401);
    }

    try {

        DB::transaction(function () use ($notification, $orderId, $grossAmount) {

            // Kunci data pembayaran agar tidak diproses bersamaan.
            $payment = Payment::where('midtrans_order_id', $orderId)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                throw new Exception('Data pembayaran tidak ditemukan.');
            }

            // Pastikan nominal sesuai dengan database.
            $notificationAmount = (int) round((float) $grossAmount * 100);
            $databaseAmount = (int) round((float) $payment->amount * 100);

            if ($notificationAmount !== $databaseAmount) {
                throw new Exception('Nominal pembayaran tidak sesuai.');
            }

            $transactionStatus = $notification['transaction_status'] ?? null;
            $fraudStatus = $notification['fraud_status'] ?? null;

            // Simpan informasi transaksi Midtrans.
            $payment->transaction_id = $notification['transaction_id'] ?? null;
            $payment->transaction_status = $transactionStatus;
            $payment->payment_type = $notification['payment_type'] ?? null;
            $payment->fraud_status = $fraudStatus;
            $payment->transaction_time = $notification['transaction_time'] ?? null;
            $payment->settlement_time = $notification['settlement_time'] ?? null;
            $payment->expiry_time = $notification['expiry_time'] ?? null;
            $payment->response_payload = $notification;

            $paymentSuccess = (
                $transactionStatus === 'settlement'
            ) || (
                $transactionStatus === 'capture'
                && $fraudStatus === 'accept'
            );

            if ($paymentSuccess) {

                // Jangan mengulang proses jika sudah terverifikasi.
                if ($payment->status === 'verified') {
                    $payment->save();
                    return;
                }

                $booking = Booking::where('id', $payment->booking_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Update status pembayaran.
                $payment->status = 'verified';
                $payment->payment_date = now();
                $payment->verified_at = now();

                // Update status booking.
                if ($booking->status === 'pending') {
                    $booking->status = 'confirmed';
                    $booking->save();
                }

                $payment->save();

                /*
                 * Proses voucher hanya satu kali.
                 */
                if ($booking->voucher_id) {

                    $existingRedemption = DB::table('voucher_redemptions')
                        ->where('voucher_id', $booking->voucher_id)
                        ->where('booking_id', $booking->id)
                        ->exists();

                    if (!$existingRedemption) {

                        DB::table('voucher_redemptions')->insert([
                            'voucher_id' => $booking->voucher_id,
                            'booking_id' => $booking->id,
                            'user_id' => $booking->user_id,
                            'discount_amount' => $booking->discount_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        DB::table('vouchers')
                            ->where('id', $booking->voucher_id)
                            ->increment('used_count');
                    }
                }

                Log::info('Midtrans payment successfully verified', [
                    'order_id' => $orderId,
                    'booking_code' => $booking->booking_code,
                ]);

            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {

                // Jangan menurunkan status pembayaran yang sudah berhasil.
                if ($payment->status !== 'verified') {
                    $payment->status = 'rejected';
                }

                $payment->save();

            } elseif ($transactionStatus === 'refund') {

                $payment->status = 'refunded';
                $payment->save();

            } else {

                // Contohnya status pending.
                $payment->save();
            }
        });

        return response()->json([
            'message' => 'Notification processed successfully.'
        ], 200);

    } catch (Exception $e) {

        Log::error('Midtrans notification processing failed', [
            'order_id' => $orderId,
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'message' => 'Notification processing failed.'
        ], 500);
    }
}
}