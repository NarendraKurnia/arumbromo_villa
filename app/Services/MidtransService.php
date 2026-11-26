<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');

        Config::$isProduction = config('services.midtrans.is_production');

        Config::$isSanitized = true;

        Config::$is3ds = true;
    }

    /**
     * Membuat transaksi Midtrans Snap.
     */
    public function createTransaction(array $params)
    {
        try {
            return Snap::createTransaction($params);
        } catch (Exception $e) {
            throw new Exception(
                'Gagal membuat transaksi Midtrans: ' . $e->getMessage()
            );
        }
    }

    /**
     * Mengambil Snap Token.
     */
    public function getSnapToken(array $params)
    {
        $transaction = $this->createTransaction($params);

        return $transaction->token;
    }
}