<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel payments
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
        'verified_at' => 'datetime',
        'response_payload' => 'array',
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(
            PaymentMethod::class,
            'payment_method_id'
        );
    }
}