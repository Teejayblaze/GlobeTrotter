<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uploaded_by_user_id');
            $table->unsignedInteger('user_type_id');
            $table->unsignedInteger('asset_booking_id');
            $table->string('booking_ref');
            $table->string('upload_name');
            $table->string('media');
            $table->string('media_type');
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
        Schema::dropIfExists('material_uploads');
    }
}
