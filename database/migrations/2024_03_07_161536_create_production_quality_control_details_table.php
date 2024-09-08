<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionQualityControlDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_quality_control_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observation');
            $table->integer('quantity');
            $table->integer('residue');
          
            $table->unsignedBigInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulo');

            $table->unsignedInteger('quality_id');
            $table->foreign('quality_id')->references('id')->on('production_qualities');

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
        Schema::dropIfExists('production_quality_control_details');
    }
}
