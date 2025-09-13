<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemissionNoteDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('remission_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remission_note_id');
            $table->unsignedBigInteger('articulo_id');
            $table->decimal('quantity', 10, 2);
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('remission_note_id')->references('id')->on('remission_notes')->onDelete('cascade');
            $table->foreign('articulo_id')->references('id')->on('articulo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('remission_note_details');
    }
}
