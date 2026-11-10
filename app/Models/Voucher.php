<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel vouchers
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'voucher_id');
    }

    public function redemptions()
    {
        return $this->hasMany(
            VoucherRedemption::class,
            'voucher_id'
        );
    }
}