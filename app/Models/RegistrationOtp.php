<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationOtp extends Model
{
    protected $table = 'registration_otps';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'otp_code',
        'channel',
        'expires_at',
        'verified_at',
        'last_sent_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];
}