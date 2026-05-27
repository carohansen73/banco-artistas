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
        Schema::create('artista_genero', function (Blueprint $table) {
            $table->foreignId('artista_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genero_id')->constrained()->cascadeOnDelete();
            $table->primary(['artista_id', 'genero_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artista_genero');
    }
};
