<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionCostDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_cost_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('raw_materials');
            $table->decimal('price_cost',11,2);
            $table->integer('quantity');
            $table->unsignedInteger('production_cost_id')->nullable();
            $table->foreign('production_cost_id')->references('id')->on('production_costs');
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
        Schema::dropIfExists('production_cost_details');
    }
}
