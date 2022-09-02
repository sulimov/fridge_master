<?php

namespace App\Services;

use App\Http\Repositories\BookedBlocksRepository;
use App\Models\BookedBlocks;
use Exception;

class BookedBlocksService
{
    private BookedBlocksRepository $bookedBlocksRepository;

    public function __construct(BookedBlocksRepository $bookedBlocksRepository)
    {
        $this->bookedBlocksRepository = $bookedBlocksRepository;
    }

    /**
     * Stores record about booked blocks
     * @param int $bookingId
     * @param int $roomId
     * @param int $blocksCount
     * @param int $temperature
     * @param string $dateFrom
     * @param string $dateTo
     * @return BookedBlocks
     * @throws Exception
     */
    public function store(int $bookingId, int $roomId, int $blocksCount, int $temperature,
                          string $dateFrom, string $dateTo): BookedBlocks
    {
        $blocksPosition = $this->bookedBlocksRepository->getBlocksPosition($roomId, $blocksCount);

        return BookedBlocks::create([
            'booking_id' => $bookingId,
            'active' => 1,
            'room_id' => $roomId,
            'blocks_start' => $blocksPosition[0],
            'blocks_end' => $blocksPosition[1],
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'booked_temperature' => $temperature,
        ]);
    }
}
