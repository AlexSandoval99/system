<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('collection_date');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('bank_id');
            $table->string('account_number');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cash_box_id');
            $table->foreign('cash_box_id')->references('id')->on('cash_boxes');
            $table->integer('status');
            $table->text('observations')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_deposits');
    }
};
