<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('purchase_id');
            $table->foreign('purchase_id')->references('id')->on('purchases');

            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('raw_materials');

            $table->string('description');
            $table->integer('quantity');
            $table->decimal('amount',11,2);
            $table->decimal('excenta',11,2)->nullable();
            $table->decimal('iva5',11,2)->nullable();
            $table->decimal('iva10',11,2)->nullable();

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
        Schema::dropIfExists('purchase_details');
    }
}
