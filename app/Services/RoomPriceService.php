<?php

namespace App\Services;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class RoomPriceService
{
    public function calculate(
        Room $room,
        string $checkIn,
        string $checkOut
    ): array {

        $start = Carbon::parse($checkIn)->startOfDay();
        $end = Carbon::parse($checkOut)->startOfDay();

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'check_out' => 'Tanggal check-out harus setelah check-in.'
            ]);
        }

        $seasons = $room->seasonalPrices()
            ->where('status', 'active')
            ->whereDate('start_date', '<', $end->toDateString())
            ->whereDate('end_date', '>=', $start->toDateString())
            ->get();

        $totalNights = $start->diffInDays($end);

        $totalAmount = 0;
        $breakdown = [];

        $currentDate = $start->copy();

        while ($currentDate->lt($end)) {

            $date = $currentDate->toDateString();

            $activeSeasons = $seasons->filter(function ($season) use ($date) {
                return $date >= $season->start_date->format('Y-m-d')
                    && $date <= $season->end_date->format('Y-m-d');
            });

            if ($activeSeasons->count() > 1) {
                throw ValidationException::withMessages([
                    'check_in' => "Terdapat periode high season yang bertumpang tindih pada tanggal {$date}."
                ]);
            }

            $season = $activeSeasons->first();

            $price = $season
                ? (float) $season->price_per_night
                : (float) $room->price_per_night;

            $totalAmount += $price;

            $breakdown[] = [
                'date' => $date,
                'season_name' => $season?->season_name ?? 'Normal Season',
                'price' => $price,
            ];

            $currentDate->addDay();
        }

        return [
            'room_id' => $room->id,
            'room_name' => $room->name,
            'total_nights' => $totalNights,
            'average_price_per_night' => round(
                $totalAmount / $totalNights,
                2
            ),
            'subtotal' => $totalAmount,
            'breakdown' => $breakdown,
        ];
    }
}