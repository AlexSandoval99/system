<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInProductionCostDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_cost_details', function (Blueprint $table) {
            $table->unsignedInteger('material_id')->nullable()->change();
            $table->decimal('hour_worker', 12, 2)->default(0)->after('price_cost');
            $table->decimal('hourly_rate', 12, 2)->default(0)->after('hour_worker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_cost_details', function (Blueprint $table) {
            $table->unsignedInteger('material_id')->change();
            $table->dropColumn('hour_worker');
            $table->dropColumn('hourly_rate');
        });
    }
}
