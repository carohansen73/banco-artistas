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
        Schema::create('artista_redes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artista_id')->constrained()->cascadeOnDelete();
            $table->enum('plataforma', [
                'youtube', 'spotify', 'instagram', 'facebook',
                'tiktok', 'web', 'soundcloud', 'behance', 'vimeo', 'otro'
            ]);
            $table->string('url');
            $table->string('nombre_personalizado')->nullable(); // solo si plataforma = 'otro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artista_redes');
    }
};
