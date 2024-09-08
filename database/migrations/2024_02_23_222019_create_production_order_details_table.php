<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->integer('quantity_material');

            $table->unsignedBIgInteger('articulo_id'); 
            $table->foreign('articulo_id')->references('id')->on('articulo');
            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('raw_materials');

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
        Schema::dropIfExists('production_order_details');
    }
}
