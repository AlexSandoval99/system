<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRawMaterialIdPurchaseMovementsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_movements_details', function (Blueprint $table) {
            $table->unsignedInteger('raw_materials_id')->nullable()->after('affects_stock');
            $table->foreign('raw_materials_id')->references('id')->on('raw_materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_movements_details', function (Blueprint $table) {
            //
        });
    }
}
