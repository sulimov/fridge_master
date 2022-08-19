<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedBlocks extends Model
{
    protected $fillable = [
        'room_id',
        'date_from',
        'date_to',
    ];

    use HasFactory;
}
