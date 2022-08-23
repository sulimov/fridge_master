<?php

namespace App\Http\Repositories;

use App\Models\BookedBlocks;
use App\Models\Room;

class BookedBlocksRepository
{
    /**
     * @param int $roomId
     * @param int $needBlocks
     * @return array|bool
     */
    public function getBlocksPosition(int $roomId, int $needBlocks) : array|bool
    {
        $activeBookedBlocks = BookedBlocks::select(['blocks_start', 'blocks_end'])
            ->where('room_id', '=', $roomId)
            ->where('active', '=', 1)
            ->orderBy('blocks_start', 'ASC')
            ->get()
            ->toArray();

        // Return position starting from 1:
        // 1) if there are no active booked blocks
        // 2) if there is enough space from the beginning to the first occupied block
        if (empty($activeBookedBlocks) || $activeBookedBlocks[0]['blocks_start'] - $needBlocks > 0) {
            return [1, $needBlocks];
        }

        // Traverse block records
        $bookedBlocksCount = count($activeBookedBlocks);
        for ($i = 1; $i < $bookedBlocksCount; $i++) {
            $currentBlocksStart = $activeBookedBlocks[$i]['blocks_start'];
            $prevBlocksEnd = $activeBookedBlocks[$i-1]['blocks_end'];

            // Return the position after the end of the previous set of blocks if there is enough space before the next
            if ($currentBlocksStart - $prevBlocksEnd - 1 >= $needBlocks) {
                return [$prevBlocksEnd + 1, $prevBlocksEnd + $needBlocks];
            }
        }

        // Return the position after the end of the last set of blocks if there is enough space before the end
        $blocksInRoomTotal = Room::find($roomId)->value('blocks');
        $lastBlocksSet = end($activeBookedBlocks);
        if ($blocksInRoomTotal - $lastBlocksSet['blocks_end'] >= $needBlocks) {
            return [$lastBlocksSet['blocks_end'] + 1, $lastBlocksSet['blocks_end'] + $needBlocks];
        }

        return false;
    }
}
