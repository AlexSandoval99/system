<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestMaterialDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('request_material_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_material_id');
            $table->unsignedInteger('material_id');
            $table->integer('quantity');
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('request_material_id')->references('id')->on('request_materials');
            $table->foreign('material_id')->references('id')->on('raw_materials');
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_material_details');
    }
}
