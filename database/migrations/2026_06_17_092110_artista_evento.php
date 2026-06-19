<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla relacional entre Artistas - Eventos
     * Multiples artistas pueden participar de multiples eventos.
     */
    public function up(): void
    {
        Schema::create('artista_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artista_id')->constrained('artistas')->cascadeOnDelete();
            $table->foreignId('evento_id')->constrained('eventos')->cascadeOnDelete();
            $table->unique(['artista_id', 'evento_id']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artista_evento');
    }
};
