<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherRedemption;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    public function calculate(
        string $code,
        float $subtotal,
        int $userId
    ): array {

        $voucher = Voucher::where(
            'code',
            strtoupper(trim($code))
        )->first();

        if (!$voucher) {
            throw ValidationException::withMessages([
                'voucher' => 'Kode voucher tidak ditemukan.'
            ]);
        }

        if ($voucher->status !== 'active') {
            throw ValidationException::withMessages([
                'voucher' => 'Voucher tidak aktif.'
            ]);
        }

        $now = now();

        if (
            $now->lt($voucher->start_date) ||
            $now->gt($voucher->end_date)
        ) {
            throw ValidationException::withMessages([
                'voucher' => 'Voucher sudah tidak berlaku.'
            ]);
        }

        if ($subtotal < $voucher->min_booking_amount) {
            throw ValidationException::withMessages([
                'voucher' => 'Minimal transaksi belum terpenuhi.'
            ]);
        }

        // Batas penggunaan keseluruhan
        if (
            !is_null($voucher->usage_limit) &&
            $voucher->used_count >= $voucher->usage_limit
        ) {
            throw ValidationException::withMessages([
                'voucher' => 'Kuota voucher sudah habis.'
            ]);
        }

        // Batas penggunaan per pelanggan
        if (!is_null($voucher->usage_per_user)) {

            $userUsage = VoucherRedemption::where(
                'voucher_id',
                $voucher->id
            )
            ->where('user_id', $userId)
            ->count();

            if ($userUsage >= $voucher->usage_per_user) {
                throw ValidationException::withMessages([
                    'voucher' => 'Kamu sudah mencapai batas penggunaan voucher.'
                ]);
            }
        }

        // Perhitungan diskon
        if ($voucher->discount_type === 'percentage') {

            $discount = $subtotal *
                ($voucher->discount_value / 100);

            if (!is_null($voucher->max_discount)) {
                $discount = min(
                    $discount,
                    (float) $voucher->max_discount
                );
            }

        } else {

            $discount = (float) $voucher->discount_value;

        }

        // Diskon tidak boleh melebihi subtotal
        $discount = min($discount, $subtotal);

        $discount = round($discount, 2);

        $totalAmount = $subtotal - $discount;

        return [
            'voucher_id' => $voucher->id,
            'voucher_code' => $voucher->code,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $totalAmount,
        ];
    }
}