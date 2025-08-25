<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voucher_collects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('voucher_id');
            $table->integer('number');
            $table->date('expiration');
            $table->decimal('amount', 15, 2);
            $table->decimal('residue', 15, 2);
            $table->timestamps();

            $table->foreign('voucher_id')->references('id')->on('vouchers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('voucher_collects');
    }
};
