<?php

namespace App\Services;

use App\Http\Repositories\RoomRepository;
use App\Models\Booking;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * @param string $name
     * @param string $email
     * @return array
     */
    public function createUser(string $name, string $email) : array
    {
        $password = Str::random(12);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        $userArray = $user->toArray();
        $userArray['password_plain_text'] = $password;
        return $userArray;
    }

    public function store(int $user_id, int $volume, string $dateFrom, string $dateTo)
    {
        $blocksCount = RoomRepository::countBlocksByVolume($volume);
        $dailyBlockPrice = Booking::DAILY_BLOCK_PRICE;

        $dateTimeFrom = new DateTime($dateFrom);
        $dateTimeTo = new DateTime($dateTo);
        $daysToStore = $dateTimeFrom->diff($dateTimeTo)->days + 1;

        return Booking::create([
            'user_id' => $user_id,
            'price' => $blocksCount * $dailyBlockPrice * $daysToStore,
            'status' => Booking::STATUS_BOOKED,
        ]);
    }
}
