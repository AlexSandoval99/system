<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameWishProductionsToWishSales extends Migration
{
    public function up()
    {
        Schema::rename('wish_productions', 'wish_sales');

        Schema::table('wish_production_details', function (Blueprint $table) {
            $table->dropForeign(['wish_production_id']);
            $table->renameColumn('wish_production_id', 'wish_sale_id');
        });

        Schema::rename('wish_production_details', 'wish_sale_details');
        Schema::table('wish_sale_details', function (Blueprint $table) {
            $table->foreign('wish_sale_id')
                  ->references('id')
                  ->on('wish_sales')
                  ->onDelete('cascade');
        });

        Schema::table('budget_productions', function (Blueprint $table) {
            $table->dropForeign(['wish_production_id']);
            $table->renameColumn('wish_production_id', 'wish_sale_id');
        });

        Schema::table('budget_productions', function (Blueprint $table) {
            $table->foreign('wish_sale_id')
                  ->references('id')
                  ->on('wish_sales')
                  ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::table('wish_production_details', function (Blueprint $table) {
            $table->dropForeign(['wish_sale_id']);
            $table->renameColumn('wish_sale_id', 'wish_production_id');
        });

        Schema::table('wish_production_details', function (Blueprint $table) {
            $table->foreign('wish_production_id')
                  ->references('id')
                  ->on('wish_productions')
                  ->onDelete('cascade');
        });

        Schema::rename('wish_sales', 'wish_productions');
    }
}
