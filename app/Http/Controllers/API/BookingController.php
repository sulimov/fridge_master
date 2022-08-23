<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\BookedBlocksRepository;
use App\Http\Repositories\RoomRepository;
use App\Http\Requests\BookRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    protected RoomRepository $roomRepository;
    protected BookingService $bookingService;
    protected BookedBlocksRepository $bookedBlocksRepository;

    public function __construct(
        RoomRepository $roomRepository,
        BookingService $bookingService,
        BookedBlocksRepository $bookedBlocksRepository,
    )
    {
        $this->roomRepository = $roomRepository;
        $this->bookedBlocksRepository = $bookedBlocksRepository;
        $this->bookingService = $bookingService;
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

        // Create user
        $userArray = $this->bookingService->createUser($request->name, $request->email);

        // Store booking
        $booking = $this->bookingService->store(
            $userArray['id'],
            $request->volume,
            $request->date_from,
            $request->dateTo
        );

        // TODO

        return new JsonResponse($userArray);
    }
}
