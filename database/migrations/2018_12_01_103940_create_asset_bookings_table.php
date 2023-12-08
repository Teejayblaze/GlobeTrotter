<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('next_availability_date');
            $table->unsignedInteger('booked_by_user_id');
            $table->unsignedInteger('user_type_id');
            $table->timestamp('grace_period_started');
            $table->unsignedInteger('asset_id');
            $table->unsignedTinyInteger('locked')->default(0);
            $table->string('trnx_id')->nullable();
            $table->unsignedTinyInteger('paycompleted')->default(0);
            $table->unsignedTinyInteger('bank_ref')->default(0);
            $table->string('price');
            $table->unsignedTinyInteger('cancel')->default(0);
            $table->foreign('asset_id')->references('id')->on('assets');
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
        Schema::dropIfExists('asset_bookings');
    }
}
