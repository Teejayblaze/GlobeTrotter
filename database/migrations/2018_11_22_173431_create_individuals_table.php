<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('token')->nullable();
            $table->unsignedInteger('corp_id')->default(0);
            $table->unsignedInteger('operator')->default(0);
            $table->string('designation')->nullable();
            $table->unsignedTinyInteger('blocked')->default(0);
            $table->unsignedTinyInteger('email_verified')->default(0);
            $table->unsignedTinyInteger('tandc')->default(0);
            $table->unsignedTinyInteger('bvn_verified')->default(0);
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
        Schema::dropIfExists('individuals');
    }
}
