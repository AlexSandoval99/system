<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('voucher_id')->nullable();
            $table->decimal('amount',11,2);
            $table->longtext('observation')->nullable();
            $table->boolean('type')->default(true);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->string('reason_deleted')->nullable();
            $table->unsignedBigInteger('user_deleted')->nullable();
            $table->timestamps();
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
