<?php

namespace App\Services;

use App\Models\Booking;
use DateTime;

class BookingService
{
    /**
     * Stores booking record
     * @param int $user_id
     * @param int $blocksCount
     * @param string $dateFrom
     * @param string $dateTo
     * @return Booking
     * @throws \Exception
     */
    public function store(int $user_id, int $blocksCount, string $dateFrom, string $dateTo): Booking
    {
        $dateTimeFrom = new DateTime($dateFrom);
        $dateTimeTo = new DateTime($dateTo);
        $daysToStore = $dateTimeFrom->diff($dateTimeTo)->days + 1;

        return Booking::create([
            'user_id' => $user_id,
            'price' => $blocksCount * Booking::DAILY_BLOCK_PRICE * $daysToStore,
            'status' => Booking::STATUS_BOOKED,
        ]);
    }
}
