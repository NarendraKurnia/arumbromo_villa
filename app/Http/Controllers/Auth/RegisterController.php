<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\RegistrationOtp;
use App\Mail\RegistrationOtpMail;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'password' => 'required|min:8|confirmed',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors([
                'email' => 'Email sudah terdaftar.'
            ])->withInput();
        }

        $otpCode = (string) random_int(100000, 999999);

        DB::beginTransaction();

        try {

            RegistrationOtp::where('email', $request->email)->delete();

            $otp = RegistrationOtp::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),

                'otp_code' => Hash::make($otpCode),
                'channel' => 'email',

                'expires_at' => now()->addMinutes(5),
                'last_sent_at' => now(),
                'attempts' => 0,
            ]);

            Mail::to($request->email)->send(
                new RegistrationOtpMail($otpCode, $request->name)
            );

            DB::commit();

            session([
                'registration_email' => $request->email
            ]);

            return redirect()->route('otp.verify')
                ->with('success', 'Kode OTP berhasil dikirim ke Gmail Anda.');

        } catch (\Throwable $e) {

    DB::rollBack();

    Log::error('Registrasi OTP gagal', [
        'email' => $request->email,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);

    return back()
        ->with('error', 'Registrasi gagal. Silakan periksa log Laravel.')
        ->withInput();
}
    }
}