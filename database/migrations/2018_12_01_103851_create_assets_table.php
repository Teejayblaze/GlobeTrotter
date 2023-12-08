<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('location');
            $table->string('location_state');
            $table->string('location_lga');
            $table->string('location_lcda');
            $table->string('address');
            $table->string('min_price');
            $table->string('max_price');
            $table->string('asset_dimension_width');
            $table->string('asset_dimension_height');
            $table->string('print_dimension')->nullable();
            $table->string('pixel_resolution')->nullable();
            $table->string('substrate')->nullable();
            $table->string('num_slides')->nullable();
            $table->string('num_slides_per_secs')->nullable();
            $table->string('file_format')->nullable();
            $table->string('file_size')->nullable();
            $table->enum('apply_promo', ['YES', 'NO'])->default('NO');
            $table->string('asset_category');
            $table->string('orientation');
            $table->string('face_count');
            $table->string('payment_freq');
            $table->string('advert_type')->nullable();
            $table->unsignedInteger('asset_type');
            $table->unsignedTinyInteger('uploaded_by');
            $table->foreign('asset_type')->references('id')->on('asset_types');
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
        Schema::dropIfExists('assets');
    }
}
