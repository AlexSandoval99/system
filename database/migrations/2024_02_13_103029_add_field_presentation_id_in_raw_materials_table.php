<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFieldPresentationIdInRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('presentation_id')->after('articulo_id')->nullable();
            $table->foreign('presentation_id')->references('id')->on('presentations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign('raw_materials_presentation_id_foreign');
            $table->dropColumn('presentation_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
