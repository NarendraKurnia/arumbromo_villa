<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;



// ==========================================
// FRONTEND
// ==========================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/kamar', [RoomController::class, 'index'])
    ->name('room.index');
// DETAIL KAMAR
Route::get('/kamar/{slug}', [RoomController::class, 'detail'])
    ->name('room.detail');

Route::get('/room/{id}', [RoomController::class, 'show'])
    ->name('rooms.show');

Route::get('/ketersediaan-kamar', [
    AvailabilityController::class,
    'index'
])->name('availability.index');

// Booking
Route::get('/booking', [BookingController::class, 'index'])
    ->name('booking.index');

Route::post('/booking', [BookingController::class, 'store'])
    ->name('booking.store');

Route::get('/booking/{bookingCode}', [BookingController::class, 'detail'])
    ->name('booking.detail');

// ==========================================
// AUTHENTICATION
// ==========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
        
    Route::get('/profil', [ProfileController::class, 'index'])
    ->name('profile.index');

    Route::get('/riwayat-booking', [ProfileController::class, 'history'])
    ->name('profile.history');

    Route::get('/register', [RegisterController::class, 'index'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.process');

});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('user.session')
    ->name('logout');

// ADMINISTRATOR

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['user.session', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [
            DashboardController::class,
            'index'
        ])->name('dashboard');

    });

// REGISTER
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.submit');


// OTP VERIFICATION
Route::get('/verify-otp', [OTPController::class, 'index'])
    ->name('otp.verify');

Route::post('/verify-otp', [OTPController::class, 'verify'])
    ->name('otp.verify.submit');


// RESEND OTP GMAIL
Route::post('/resend-otp-email', [OTPController::class, 'resendEmail'])
    ->name('otp.resend.email');

Route::post('/send-otp-whatsapp', [OTPController::class, 'sendWhatsapp'])
    ->name('otp.send.whatsapp');

// villa

Route::get('/villa', [VillaController::class, 'index'])
    ->name('villa.index');

Route::get('/villa/{id}', [VillaController::class, 'detail'])
    ->name('villa.detail');