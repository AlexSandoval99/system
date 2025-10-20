<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budget_purchase_details', function (Blueprint $table) {
            // Cambiamos el tipo a DECIMAL(15,2)
            $table->decimal('price', 15, 2)->change();
            $table->decimal('total_price', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('budget_purchase_details', function (Blueprint $table) {
            // Revertir al tamaño anterior, si era decimal(8,2)
            $table->decimal('price', 8, 2)->change();
            $table->decimal('total_price', 8, 2)->change();
        });
    }
};

