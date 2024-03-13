<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldLosseIdInLossesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('losse_details', function (Blueprint $table) {
            $table->unsignedInteger('losse_id')->nullable()->after('reason');
            $table->foreign('losse_id')->references('id')->on('losses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('losse_details', function (Blueprint $table) {
            //
        });
    }
}
