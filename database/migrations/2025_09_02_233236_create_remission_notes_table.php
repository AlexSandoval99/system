<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemissionNotesTable extends Migration
{
    public function up()
    {
        Schema::create('remission_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('branch_id');
            $table->unsignedInteger('voucher_box_id');
            $table->unsignedInteger('stamped_id');
            $table->unsignedInteger('client_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('user_id');

            $table->string('number', 20);
            $table->date('remission_date');


            $table->string('delivery_address', 255)->nullable();
            $table->string('delivery_city', 100)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('observation')->nullable();
            $table->string('status', 20)->default('Pendiente');

            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('voucher_box_id')->references('id')->on('voucher_boxes');
            $table->foreign('stamped_id')->references('id')->on('stampeds');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('remission_notes');
    }
}
