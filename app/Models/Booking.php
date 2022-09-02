<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    // In a real system this value must be stored in the database
    const DAILY_BLOCK_PRICE = 5;

    const STATUS_BOOKED = 1;
    const STATUS_CANCELED = 2;
    const STATUS_PAYED = 3;
    const STATUS_REFUNDED = 4;

    protected $fillable = [
        'user_id',
        'price',
        'status',
    ];

    public function booked_blocks() : HasMany
    {
        return $this->hasMany(BookedBlocks::class);
    }
}
