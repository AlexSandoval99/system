<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldStageIdInSettingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_products', function (Blueprint $table) {

            $table->unsignedBigInteger('stage_id')->nullable()->after('articulo_id');
            $table->foreign('stage_id')->references('id')->on('production_stages');

            $table->unsignedInteger('raw_materials_id')->nullable()->change();
            $table->unsignedBigInteger('articulo_id')->nullable()->change();
            $table->integer('quantity')->nullable()->change();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_products', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
        });
    }
}
