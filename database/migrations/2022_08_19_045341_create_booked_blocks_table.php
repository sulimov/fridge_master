<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->tinyInteger('active');
            $table->unsignedBigInteger('room_id');
            $table->unsignedInteger('blocks_start');
            $table->unsignedInteger('blocks_end');
            $table->unsignedInteger('blocks_total')->storedAs('blocks_end - blocks_start + 1');
            $table->date('date_from');
            $table->date('date_to');
            $table->smallInteger('booked_temperature');
            $table->timestamps();

            $table
                ->foreign('booking_id')
                ->references('id')
                ->on('bookings');

            $table
                ->foreign('room_id')
                ->references('id')
                ->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_blocks');
    }
};
