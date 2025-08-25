<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOrderProductionIdInProductionCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_costs', function (Blueprint $table) {
            $table->unsignedInteger('order_production_id')->after('status');
            $table->foreign('order_production_id')->references('id')->on('production_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_costs', function (Blueprint $table) {
            //
        });
    }
}
