<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'gateway',
        'payment_method_id',
        'payment_code',
        'midtrans_order_id',
        'transaction_id',
        'snap_token',
        'redirect_url',
        'amount',
        'payment_date',
        'proof_image',
        'status',
        'transaction_status',
        'payment_type',
        'fraud_status',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'response_payload',
        'verified_by',
        'verified_at',
        'admin_note',
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