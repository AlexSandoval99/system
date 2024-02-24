<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielProductionOrderIdInProductionOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_order_details', function (Blueprint $table) {
            $table->unsignedInteger('production_order_id')->after('quantity_material');
            $table->foreign('production_order_id')->references('id')->on('production_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_order_details', function (Blueprint $table) {
            $table->dropForeign(['production_order_id']);
            $table->dropColumn('production_order_id');
        });
    }
}
