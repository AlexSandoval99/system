<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldProductionQualityControlsIdInProductionQualityControlDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_quality_control_details', function (Blueprint $table) {
            $table->unsignedInteger('production_quality_id')->nullable()->after('residue');
            $table->foreign('production_quality_id')->references('id')->on('production_quality_controls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_quality_control_details', function (Blueprint $table) {
            //
        });
    }
}
