<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAmountCheckInCollectionDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_deposits', function (Blueprint $table) {
            $table->decimal('amount_check', 11, 2)->after('amount')->default(0);
            $table->renameColumn('amount', 'amount_cash');
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
            $table->dropColumn('amount_check');
        });
    }
}
