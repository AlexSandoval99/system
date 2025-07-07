<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldNameInCashBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_boxes', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->longtext('observation')->nullable()->after('name');

            $table->unsignedInteger('voucher_box_id')->after('observation');
            $table->foreign('voucher_box_id')->references('id')->on('voucher_boxes');

            $table->boolean('status')->default(true)->after('voucher_box_id');


            $table->unsignedBigInteger('user_id')->after('status');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_boxes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['voucher_box_id']);
            $table->dropColumn('voucher_box_id');
            $table->dropColumn('status');
            $table->dropColumn('observation');
            $table->dropColumn('name');
        });
    }
}
