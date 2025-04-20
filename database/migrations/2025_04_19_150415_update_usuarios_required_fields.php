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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('nombre')->nullable(false)->change();
            $table->string('celular')->nullable(false)->change();
            $table->string('direccion')->nullable(false)->change();

            // Los siguientes campos siguen pudiendo ser nulos
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('rol')->nullable()->change();
            $table->boolean('activo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
