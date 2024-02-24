<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wish_production_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');

            $table->unsignedBIgInteger('articulo_id'); 
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->unsignedInteger('wish_production_id'); 
            $table->foreign('wish_production_id')->references('id')->on('wish_productions');

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
        Schema::dropIfExists('wish_production_details');
    }
}
