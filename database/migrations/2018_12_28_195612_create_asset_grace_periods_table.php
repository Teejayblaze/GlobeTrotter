<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetGracePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_grace_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_booking_id');
            $table->string('percentage');
            $table->string('booked_id');
            $table->timestamp('grace_period_started')->nullable();
            $table->timestamp('grace_period_ends')->nullable();
            $table->unsignedTinyInteger('completed')->default(0);
            $table->foreign('asset_booking_id')->references('id')->on('asset_bookings');
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
        Schema::dropIfExists('asset_grace_periods');
    }
}
