<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielWishProductionInBudgetProductionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_production_details', function (Blueprint $table) {
            $table->unsignedInteger('wish_production_id')->nullable()->after('budget_production_id'); 
            $table->foreign('wish_production_id')->references('id')->on('wish_productions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_production_details', function (Blueprint $table) {
            //
        });
    }
}
