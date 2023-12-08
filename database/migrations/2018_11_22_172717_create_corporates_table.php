<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('website')->index();
            $table->string('rc_number')->unique();
            $table->string('address');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('token')->index();
            $table->unsignedTinyInteger('email_verified');
            $table->unsignedTinyInteger('blocked')->default(0);
            $table->unsignedTinyInteger('tandc')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedTinyInteger('active')->default(0);
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
        Schema::dropIfExists('corporates');
    }
}
