<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\LoginController;


use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\VillaController;
use App\Http\Controllers\Frontend\RoomController;

use App\Http\Controllers\Admin\DashboardController;



// ==========================================
// FRONTEND
// ==========================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/villas', [VillaController::class, 'index'])
    ->name('villas.index');

Route::get('/villas/{slug}', [VillaController::class, 'show'])
    ->name('villas.show');

Route::get('/room/{id}', [RoomController::class, 'show'])
    ->name('rooms.show');


// ==========================================
// AUTHENTICATION
// ==========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [RegisterController::class, 'index'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.process');

});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('user.session')
    ->name('logout');


// ==========================================
// ADMINISTRATOR
// ==========================================

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