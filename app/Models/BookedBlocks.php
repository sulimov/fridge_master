<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedBlocks extends Model
{
    const MAX_STORAGE_DAYS = 24;

    protected $fillable = [
        'booking_id',
        'active',
        'room_id',
        'blocks_start',
        'blocks_end',
        'date_from',
        'date_to',
        'booked_temperature',
    ];

    use HasFactory;
}
