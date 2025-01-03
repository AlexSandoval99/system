<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('status');

            $table->unsignedInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedInteger('wish_id')->nullable();
            $table->foreign('wish_id')->references('id')->on('wish_purchases');
            $table->string('name')->nullable();
            $table->string('ruc')->nullable();
            // $table->unsignedInteger('branch_id');
            // $table->foreign('branch_id')->references('id')->on('branches');

            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wish_purchases');
    }
}
