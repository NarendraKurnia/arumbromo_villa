<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        // Sesuaikan dengan kolom tabel payment_methods
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
}