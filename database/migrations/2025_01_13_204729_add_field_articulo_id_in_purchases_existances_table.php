<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldArticuloIdInPurchasesExistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases_existences', function (Blueprint $table) {
            $table->unsignedBIgInteger('articulo_id')->nullable()->after('raw_material_id');
            $table->foreign('articulo_id')->references('id')->on('articulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_existences', function (Blueprint $table) {
            //
        });
    }
}
