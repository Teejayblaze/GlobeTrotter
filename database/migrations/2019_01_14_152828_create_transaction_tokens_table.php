<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->unique();
            $table->string('payment_id')->nullable();
            $table->string('trnx_id')->nullable();
            $table->string('used_by')->nullable();
            $table->string('corp_id')->nullable();
            $table->string('admin_user_id')->nullable();
            $table->string('admin_user_type_id')->nullable();
            $table->enum('status', ['used','unused']);
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
        Schema::dropIfExists('transaction_tokens');
    }
}
