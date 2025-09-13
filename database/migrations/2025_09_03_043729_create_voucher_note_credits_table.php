<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherNoteCreditsTable extends Migration
{
    public function up()
    {
        Schema::create('voucher_note_credits', function (Blueprint $table) {
            $table->increments('id');

            // Relación con vouchers
            $table->unsignedInteger('voucher_id');
            $table->foreign('voucher_id')->references('id')->on('vouchers');

            // Relación con invoices (puede ser vouchers si es la misma tabla de facturas)
            $table->unsignedInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('vouchers');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voucher_note_credits');
    }
}
