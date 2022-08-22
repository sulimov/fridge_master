<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    const STATUS_BOOKED = 1;
    const STATUS_CANCELED = 2;
    const STATUS_PAYED = 3;
    const STATUS_REFUNDED = 4;

    public function booked_blocks() : HasMany
    {
        return $this->hasMany(BookedBlocks::class);
    }
}
