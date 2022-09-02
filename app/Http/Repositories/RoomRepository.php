<?php

namespace App\Http\Repositories;

use App\Models\Room;

class RoomRepository
{
    /**
     * Returns id of room that contains free blocks to store a given volume
     * @param int $locationId
     * @param int $blocksCount
     * @param int $temperature
     * @param string $dateFrom
     * @param string $dateTo
     * @return int|null
     */
    public function getRoomIdWithAvailableBlocks(int $locationId, int $blocksCount, int $temperature,
                                                 string $dateFrom, string $dateTo): int|null
    {
        // Get min/max temperature by condition: the temperature must be within
        // +-2 degrees of the ordered, without crossing the border of 0 degrees
        $temperatureRange = [$temperature - 2, min($temperature + 2, -1)];

        return Room::from('rooms as r')
            ->select('r.id')
            ->where('r.location_id', '=', $locationId)
            ->whereBetween('r.temperature', $temperatureRange)
            ->leftJoin('booked_blocks as bb', function ($join) use ($dateFrom, $dateTo) {
                $join
                    ->on('bb.room_id', '=', 'r.id')
                    ->where('bb.active', '=', 1)
                    ->where('bb.date_from', '<=', $dateTo)
                    ->where('bb.date_to', '>=', $dateFrom);
            })
            ->groupByRaw('r.id, r.blocks')
            ->havingRaw('r.blocks - IFNULL(SUM(bb.blocks_total), 0) >= ?', [$blocksCount])
            ->value('r.id');
    }

    /**
     * @param int $id
     * @param string $column
     * @return mixed
     */
    public function getColValueById(int $id, string $column): mixed
    {
        return Room::find($id)->value($column);
    }
}
