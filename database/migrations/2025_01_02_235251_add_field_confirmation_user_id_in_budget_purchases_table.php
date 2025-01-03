<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldConfirmationUserIdInBudgetPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('confirmation_user_id')->nullable()->after('ruc');
            $table->foreign('confirmation_user_id')->references('id')->on('users');

            $table->datetime('confirmation_date')->nullable()->after('confirmation_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_purchases', function (Blueprint $table) {
            //
        });
    }
}
