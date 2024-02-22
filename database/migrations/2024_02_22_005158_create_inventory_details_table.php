<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('inventory_id');
            $table->foreign('inventory_id')->references('id')->on('inventories');

            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('raw_materials');

            $table->integer('quantity');
            $table->integer('existence');
            $table->decimal('old_cost', 11,2);

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
        Schema::dropIfExists('inventory_details');
    }
}
