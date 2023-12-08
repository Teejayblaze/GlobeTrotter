<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Operators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('corporate_name');
            $table->string('rc_number');
            $table->string('oaan_number');
            $table->string('email');
            $table->string('phone');
            $table->string('token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedTinyInteger('email_verified')->default(0);
            $table->unsignedTinyInteger('active')->default(0);
            $table->unsignedTinyInteger('blocked')->default(0);
            $table->unsignedTinyInteger('verified')->default(0);
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
        Schema::dropIfExists('operators');
    }
}
