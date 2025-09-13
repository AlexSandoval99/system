<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 100);    // Marca
            $table->string('model', 100);    // Modelo
            $table->string('plate', 20);     // Matrícula / chapa
            $table->string('driver_name', 150);  // Conductor
            $table->string('driver_document', 50); // CI del conductor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
