<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielCashBoxDetailIdInCollectionDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_deposits', function (Blueprint $table) {
            $table->unsignedInteger('cash_box_detail_id')->after('cash_box_id')->nullable();
            $table->foreign('cash_box_detail_id')->references('id')->on('cash_box_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_deposits', function (Blueprint $table) {
            //
        });
    }
}
