<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldInBudgetProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_productions', function (Blueprint $table) {

            $table->unsignedInteger('wish_production_id')->nullable()->after('client_id');
            $table->foreign('wish_production_id')->references('id')->on('wish_productions');

        });

        Schema::table('production_orders', function (Blueprint $table) {

            $table->unsignedInteger('budget_production_id')->nullable()->after('user_id');
            $table->foreign('budget_production_id')->references('id')->on('budget_productions');

        });

        Schema::table('production_controls', function (Blueprint $table) {

            $table->unsignedInteger('production_order_id')->nullable()->after('user_id');
            $table->foreign('production_order_id')->references('id')->on('production_orders');

        });

        Schema::table('production_quality_controls', function (Blueprint $table) {

            $table->unsignedInteger('production_control_id')->nullable()->after('user_id');
            $table->foreign('production_control_id')->references('id')->on('production_controls');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_productions', function (Blueprint $table) {
            //
        });
    }
}
