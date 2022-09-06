<?php

namespace App\Services;

use App\Http\Repositories\BookedBlocksRepository;
use App\Http\Repositories\RoomRepository;
use App\Http\Requests\BookRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class BookService
{
    protected RoomRepository $roomRepository;
    protected BookingService $bookingService;
    protected BookedBlocksRepository $bookedBlocksRepository;
    protected BookedBlocksService $bookedBlocksService;

    public function __construct(
        RoomRepository         $roomRepository,
        BookingService         $bookingService,
        BookedBlocksRepository $bookedBlocksRepository,
        BookedBlocksService    $bookedBlocksService,
    )
    {
        $this->roomRepository = $roomRepository;
        $this->bookingService = $bookingService;
        $this->bookedBlocksRepository = $bookedBlocksRepository;
        $this->bookedBlocksService = $bookedBlocksService;
    }

    /**
     * @param BookRequest $request
     * @return array
     * @throws \Throwable
     */
    public function store(BookRequest $request): array
    {
        // Count how much blocks are needed to store the specified volume
        $blocksCount = $this->countBlocksByVolume($request->volume);

        // Get suitable room id
        $roomId = $this->roomRepository->getRoomIdWithAvailableBlocks(
            $request->location_id,
            $blocksCount,
            $request->temperature,
            $request->date_from,
            $request->date_to,
        );

        if (empty($roomId)) {
            throw new Exception('No available blocks to store specified volume on given dates');
        }

        DB::beginTransaction();

        try {
            // Store booking
            $userId = Auth()->id();
            $booking = $this->bookingService->store($userId, $blocksCount, $request->date_from, $request->date_to);

            // Store booked blocks
            $this->bookedBlocksService->store(
                $booking->id,
                $roomId,
                $blocksCount,
                $request->temperature,
                $request->date_from,
                $request->date_to,
            );

            DB::commit();

            return $booking->toArray();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    /**
     * Returns number of blocks needed to store a given volume. Block dimensions: 2m x 1m x 1m
     * @param int $volume
     * @return int
     */
    private function countBlocksByVolume(int $volume): int
    {
        return ceil($volume / 2);
    }
}
