<?php

namespace App\Http\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function getRoomWithAvailableBlocks(int $location, int $volume, int $temperature, string $dateFrom, string $dateTo)
    {
        // Get min/max temperature by condition: the temperature must be within
        // +-2 degrees of the ordered, without crossing the border of 0 degrees
        $temperatureRange = [$temperature - 2, min($temperature + 2, -1)];

        $blocksNumber = self::countBlocksByVolume($volume);

        return Room::from('rooms as r')
            ->select('r.id')
            ->where('r.location_id', '=', $location)
            ->whereBetween('r.temperature', $temperatureRange)
            ->leftJoin('booked_blocks as bb', function ($join) use ($dateFrom, $dateTo) {
                $join
                    ->on('bb.room_id', '=', 'r.id')
                    ->where('bb.active', '=', 1)
                    ->where('bb.date_from', '<=', $dateTo)
                    ->where('bb.date_to', '>=', $dateFrom);
            })
            ->groupByRaw('r.id, r.blocks')
            ->havingRaw('r.blocks - IFNULL(SUM(bb.blocks_total), 0) >= ?', [$blocksNumber])
            ->value('r.id');
    }

    /**
     * @param int $volume
     * @return int
     */
    public static function countBlocksByVolume(int $volume) : int
    {
        return ceil($volume / 2);
    }


}
