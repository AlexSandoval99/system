<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_production_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->decimal('amount', 13,2);

            $table->unsignedInteger('budget_production_id');
            $table->foreign('budget_production_id')->references('id')->on('budget_productions');
            $table->unsignedBIgInteger('articulo_id'); 
            $table->foreign('articulo_id')->references('id')->on('articulo');

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
        Schema::dropIfExists('budget_production_details');
    }
}
