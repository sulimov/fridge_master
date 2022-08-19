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
            $table->tinyInteger('status');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id');
            $table->integer('blocks_start');
            $table->integer('blocks_end');
            $table->integer('date_from');
            $table->integer('date_to');
            $table->timestamps();
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
