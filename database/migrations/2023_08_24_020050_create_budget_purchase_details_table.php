<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_purchase_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            // $table->unsignedInteger('deposit_id');

            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('raw_materials');

            $table->unsignedInteger('budget_id');
            $table->foreign('budget_id')->references('id')->on('budget_purchases');
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
        Schema::dropIfExists('wish_purchase_details');
    }
}
