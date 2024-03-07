<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldProductionQualitiesIdInSettingProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_products', function (Blueprint $table) {
            $table->unsignedInteger('production_qualities_id')->nullable()->after('stage_id');
            $table->foreign('production_qualities_id')->references('id')->on('production_qualities');
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
            //
        });
    }
}
