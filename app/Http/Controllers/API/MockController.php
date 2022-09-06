<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class MockController extends Controller
{
    public function getLocations(): JsonResponse
    {
        return new JsonResponse([
            [
                'id' => 1,
                'name' => 'Wilmington',
                'blocks' => [
                    'total' => 4000,
                    'available' => 2500,
                ]
            ], [
                'id' => 2,
                'name' => 'Portland',
                'blocks' => [
                    'total' => 5000,
                    'available' => 2800,
                ]
            ]
        ]);
    }

    public function calc(): JsonResponse
    {
        return new JsonResponse([
            'blocks' => [
                'need' => 40,
                'available' => 1200
            ],
            'price' => 2000,
        ]);
    }

    public function myBookings(): JsonResponse
    {
        return new JsonResponse([
            'bookings' => [
                [
                    'id' => 1,
                    'price' => 1600,
                    'status' => 'Paid',
                ],
                [
                    'id' => 2,
                    'price' => 2000,
                    'status' => 'Booked',
                ]
            ],
            'total_paid' => 1600,
            'to_pay' => 1000,
            'location_date' => '2022-09-10',
        ]);
    }

    public function myBooking(Booking $booking): JsonResponse
    {
        return new JsonResponse([
            'id' => 1,
            'price' => 1600,
            'status' => 'Paid',
            'access_code' => '3489fj332d23',
            'created_at' => '2022-09-03 12:00:01',
            'updated_at' => '2022-09-03 12:00:01',
            'date_from' => '2022-09-06',
            'date_to' => '2022-09-16',
        ]);
    }
}
