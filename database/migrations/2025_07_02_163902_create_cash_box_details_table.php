<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashBoxDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_box_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('cash_box_id');
            $table->foreign('cash_box_id')->references('id')->on('cash_boxes');

            $table->unsignedInteger('cash_box_concept_id');
            $table->foreign('cash_box_concept_id')->references('id')->on('cash_box_concepts');

            $table->unsignedInteger('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('id')->on('vouchers');

            $table->unsignedInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments');

            $table->boolean('type')->default(true);
            $table->decimal('amount',11,2);
            $table->longtext('observation')->nullable();
            $table->boolean('status')->default(true);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('cash_box_details');
    }
}
