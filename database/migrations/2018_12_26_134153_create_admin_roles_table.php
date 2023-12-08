<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_user_group_id');
            $table->string('component');
            $table->unsignedTinyInteger('can_view')->default(0);
            $table->unsignedTinyInteger('can_update')->default(0);
            $table->unsignedTinyInteger('can_create')->default(0);
            $table->unsignedTinyInteger('can_delete')->default(0);
            $table->foreign('admin_user_group_id')->references('id')->on('admin_groups');
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
        Schema::dropIfExists('admin_roles');
    }
}
