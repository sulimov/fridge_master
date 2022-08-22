<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\RoomRepository;
use App\Http\Requests\BookRequest;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    protected RoomRepository $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function book(BookRequest $request)
    {
        // Get room id to storage products
        $roomId = $this->roomRepository->getRoomWithAvailableBlocks(
            $request->location_id,
            $request->volume,
            $request->temperature,
            $request->date_from,
            $request->date_to
        );

        if (empty($roomId)) {
            return new JsonResponse('No available blocks to store specified volume on given dates', 422);
        }

        // TODO

        return new JsonResponse();
    }
}
