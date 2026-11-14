<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\RegistrationOtp;
use App\Mail\RegistrationOtpMail;

class OTPController extends Controller
{
    // Halaman verifikasi OTP
    public function index()
    {
        $email = session('registration_email');

        if (!$email) {
            return redirect()->route('register');
        }

        $otp = RegistrationOtp::where('email', $email)
            ->latest()
            ->first();

        if (!$otp) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', compact('otp'));
    }


    // Verifikasi OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('registration_email');

        if (!$email) {
            return redirect()->route('register');
        }

        $otp = RegistrationOtp::where('email', $email)
            ->latest()
            ->first();

        if (!$otp) {
            return redirect()->route('register')
                ->with('error', 'Data registrasi tidak ditemukan.');
        }

        if (now()->greaterThan($otp->expires_at)) {
            return back()->with('error', 'Kode OTP sudah kedaluwarsa.');
        }

        if ($otp->attempts >= 5) {
            return back()->with('error', 'Batas percobaan OTP telah tercapai.');
        }

        if (!Hash::check($request->otp, $otp->otp_code)) {

            $otp->increment('attempts');

            return back()->with('error', 'Kode OTP yang dimasukkan salah.');
        }

        DB::beginTransaction();

        try {

            if (User::where('email', $otp->email)->exists()) {
                throw new \Exception('Email sudah terdaftar.');
            }

            User::create([
                'name' => $otp->name,
                'email' => $otp->email,
                'phone' => $otp->phone,
                'password' => $otp->password,
                'role' => 'customer',
                'status' => 'active',
            ]);

            $otp->update([
                'verified_at' => now()
            ]);

            $otp->delete();

            DB::commit();

            session()->forget('registration_email');

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Verifikasi gagal. Silakan coba kembali.'
            );
        }
    }


    // Kirim ulang OTP Gmail
    public function resendEmail()
    {
        $email = session('registration_email');

        $otp = RegistrationOtp::where('email', $email)
            ->latest()
            ->first();

        if (!$otp) {
            return redirect()->route('register');
        }

        if ($otp->last_sent_at &&
            $otp->last_sent_at->diffInSeconds(now()) < 60) {

            return back()->with(
                'error',
                'Tunggu 60 detik sebelum meminta OTP kembali.'
            );
        }

        $otpCode = (string) random_int(100000, 999999);

        try {

            Mail::to($otp->email)->send(
                new RegistrationOtpMail($otpCode, $otp->name)
            );

            $otp->update([
                'otp_code' => Hash::make($otpCode),
                'channel' => 'email',
                'expires_at' => now()->addMinutes(5),
                'last_sent_at' => now(),
                'attempts' => 0,
            ]);

            return back()->with('success', 'OTP baru berhasil dikirim ke Gmail.');

        } catch (\Throwable $e) {

            return back()->with('error', 'Gagal mengirim OTP Gmail.');
        }
    }


    // Kirim OTP melalui WhatsApp
    public function sendWhatsapp()
{
    $otp = RegistrationOtp::where('email', session('registration_email'))
        ->latest()
        ->first();

    if (!$otp) {
        return redirect()->route('register')
            ->with('error', 'Data registrasi tidak ditemukan. Silakan registrasi ulang.');
    }

    // Cek cooldown 60 detik
    if ($otp->last_sent_at) {
        $elapsed = now()->timestamp - $otp->last_sent_at->timestamp;

        if ($elapsed < 60) {
            return back()->with(
                'error',
                'Silakan tunggu ' . (60 - $elapsed) . ' detik sebelum meminta OTP kembali.'
            );
        }
    }

    $token = config('services.fonnte.token');

    if (empty($token)) {
        return back()->with('error', 'Token Fonnte belum dikonfigurasi.');
    }

    // Normalisasi nomor telepon
    $phone = preg_replace('/[^0-9]/', '', $otp->phone);

    if (str_starts_with($phone, '0')) {
        $phone = '62' . substr($phone, 1);
    } elseif (str_starts_with($phone, '8')) {
        $phone = '62' . $phone;
    }

    // Buat OTP baru
    $otpCode = (string) random_int(100000, 999999);

    $message = "Halo {$otp->name},\n\n"
        . "Kode OTP Arum Bromo Villas kamu adalah: {$otpCode}\n\n"
        . "Kode berlaku selama 5 menit. Jangan bagikan kode ini kepada siapa pun.";

    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->timeout(30)->post(
            'https://api.fonnte.com/send',
            [
                'target' => (string) $phone,
                'message' => $message,
                'countryCode' => '0',
            ]
        );

        $result = $response->json();

        // Catat respons Fonnte untuk mempermudah pemeriksaan
        \Illuminate\Support\Facades\Log::info('Respons Fonnte OTP', [
            'http_status' => $response->status(),
            'response' => $result,
        ]);

        if (!$response->successful() || !($result['status'] ?? false)) {
            \Illuminate\Support\Facades\Log::error('Fonnte gagal mengirim OTP', [
                'response' => $result,
            ]);

            $reason = $result['reason'] ?? 'Fonnte menolak pengiriman pesan.';

            return back()->with(
                'error',
                'OTP WhatsApp gagal dikirim: ' . $reason
            );
        }

        // Simpan OTP baru hanya jika pengiriman berhasil
        $otp->update([
            'otp_code' => \Illuminate\Support\Facades\Hash::make($otpCode),
            'channel' => 'whatsapp',
            'expires_at' => now()->addMinutes(5),
            'last_sent_at' => now(),
            'attempts' => 0,
        ]);

        return back()->with(
            'success',
            'OTP berhasil dikirim melalui WhatsApp. Silakan periksa pesan WhatsApp kamu.'
        );

    } catch (\Throwable $e) {

    \Illuminate\Support\Facades\Log::error(
        'Kesalahan pengiriman OTP WhatsApp',
        [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'email' => $otp->email ?? null,
        ]
    );

    return back()->with(
        'error',
        'Terjadi kesalahan koneksi saat mengirim OTP WhatsApp. Silakan coba kembali.'
    );

    }
}
}