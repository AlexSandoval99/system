<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionControlDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_control_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observation');
            $table->integer('quantity');
            $table->integer('residue');
            $table->boolean('stage');
          
            $table->unsignedBigInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulos');

            $table->unsignedBigInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('production_stages');

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
        Schema::dropIfExists('production_control_details');
    }
}
