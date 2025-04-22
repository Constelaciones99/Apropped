<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('boletas', function (Blueprint $table) {

            // Nos aseguramos que order_id exista, pero sin duplicar
            if (!Schema::hasColumn('boletas', 'order_id')) {
                $table->unsignedBigInteger('order_id')->after('user_id');
            }

            // Agregar clave foránea
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boletas', function (Blueprint $table) {
            //
        });
    }
};
