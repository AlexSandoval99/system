<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('request_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('deposit_id')->nullable();
            $table->date('request_date');
            $table->integer('status');
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('deposit_id')->references('id')->on('deposits');
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_materials');
    }
}
