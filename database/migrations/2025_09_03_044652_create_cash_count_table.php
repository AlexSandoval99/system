<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashCountTable extends Migration
{
    public function up()
    {
        Schema::create('cash_count', function (Blueprint $table) {
            $table->increments('id');

            // Relación con cash_box_details
            $table->unsignedInteger('cash_box_detail_id');
            $table->foreign('cash_box_detail_id')
                  ->references('id')
                  ->on('cash_box_details');

            // Billete (ej: 10.000, 20.000)
            $table->decimal('billet', 11, 2);

            // Cantidad de billetes
            $table->integer('quantity');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_count');
    }
}
