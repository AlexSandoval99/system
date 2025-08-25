<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherCollectPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('voucher_collect_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('voucher_id');
            $table->unsignedBigInteger('voucher_collect_id');
            $table->decimal('amount', 11, 2);
            $table->timestamps();
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->foreign('voucher_collect_id')->references('id')->on('voucher_collects');
        });
    }

    public function down()
    {
        Schema::dropIfExists('voucher_collect_payments');
    }
}
