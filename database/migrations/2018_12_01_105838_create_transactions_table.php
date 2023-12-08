<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tranx_id');
            $table->unsignedInteger('asset_booking_id');
            $table->foreign('asset_booking_id')->references('id')->on('asset_bookings');
            $table->string('description');
            $table->string('amount');
            $table->string('percentage');
            $table->unsignedTinyInteger('paid')->default(0);
            $table->unsignedTinyInteger('first_pay')->default(0);
            $table->enum('payment_type', ['cash', 'transfer', 'cheque'])->nullable();
            $table->enum('cheque_status', ['pending', 'lodged', 'cleared'])->nullable();
            $table->string('bank_ref_number')->nullable();
            $table->string('asset_booking_ref');
            $table->unsignedTinyInteger('disbursed')->default(0)->nullable();
            $table->date('date_disbursed')->nullable();
            $table->date('expect_pay_date')->nullable();
            $table->unsignedTinyInteger('cancel')->default(0)->nullable();
            $table->text('extra_info')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
