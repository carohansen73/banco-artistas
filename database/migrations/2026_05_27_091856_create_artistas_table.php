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
        Schema::create('artistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained();

            // Campos solo visibles para admin
            $table->string('telefono')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('rol_proyecto')->nullable();

            $table->string('nombre_artistico');
            $table->string('localidad');
            $table->string('slug')->unique();
            $table->string('img_perfil')->nullable();

            // Info artística
            $table->text('descripcion_actividad');
            $table->json('integrantes')->nullable(); // null = solista
            $table->boolean('tiene_formacion')->default(false);
            $table->text('detalle_formacion')->nullable();
            $table->year('anio_inicio');

            // Administrativo (solo visible para admin)
            $table->boolean('tiene_documentacion')->default(false);
            $table->boolean('acepta_difusion')->default(false);

            // Estado
            $table->boolean('visible')->default(false); // admin lo activa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artistas');
    }
};
