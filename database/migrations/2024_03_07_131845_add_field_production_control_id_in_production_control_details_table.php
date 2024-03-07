<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldProductionControlIdInProductionControlDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_control_details', function (Blueprint $table) {
            $table->unsignedInteger('production_control_id')->nullable()->after('stage');
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
        Schema::table('production_control_details', function (Blueprint $table) {
            //
        });
    }
}
