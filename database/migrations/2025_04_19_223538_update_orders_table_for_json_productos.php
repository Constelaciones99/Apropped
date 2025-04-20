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
        Schema::table('orders', function (Blueprint $table) {
            // 🔓 Eliminar clave foránea primero
            $table->dropForeign(['producto_id']);

            // 🧹 Luego elimina la columna
            $table->dropColumn('producto_id');

            // Elimina cantidad
            $table->dropColumn('cantidad');

            // Renombra total → productos
            $table->renameColumn('total', 'productos');
        });

        // Cambiar tipo a JSON
        Schema::table('orders', function (Blueprint $table) {
            $table->json('productos')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->integer('cantidad')->default(1);
            $table->renameColumn('productos', 'total');
            $table->decimal('total', 10, 2)->change();
        });
    }
};
