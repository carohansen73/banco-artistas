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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin')->nullable();
            $table->string('lugar');
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('imagen_portada')->nullable();
            $table->string('link_entradas')->nullable();
            $table->string('link_externo')->nullable();
            $table->boolean('destacado')->default(true);
            $table->boolean('activo')->default(true);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
