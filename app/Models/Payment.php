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
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'response_payload' => 'array',
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