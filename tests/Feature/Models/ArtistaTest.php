<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Artista;
use App\Models\User;
use App\Models\Disciplina;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature Tests - Probamos con BD real (en memoria)
 */
class ArtistaTest extends TestCase
{
    use RefreshDatabase; // Reinicia la BD después de cada test

    /**
     * Test: Crear un artista y guardar en BD
     */
    public function test_crear_artista_en_bd()
    {
        $user = User::factory()->create();
        $disciplina = Disciplina::factory()->create();

        $artista = Artista::create([
            'user_id'               => $user->id,
            'disciplina_id'         => $disciplina->id,
            'nombre_artistico'      => 'Los Redondos',
            'localidad'             => 'Quilmes',
            'slug'                  => 'los-redondos-xyz123',
            'descripcion_actividad' => 'Rock argentino',
            'anio_inicio'           => 1980,
            'tiene_formacion'       => false,
            'tiene_documentacion'   => false,
            'acepta_difusion'       => true,
            'visible'               => false,
        ]);

        // Verificar que se guardó
        $this->assertDatabaseHas('artistas', [
            'nombre_artistico' => 'Los Redondos',
            'localidad'        => 'Quilmes',
        ]);

        // Verificar que está en la BD
        $this->assertNotNull($artista->id);
        $this->assertEquals('Los Redondos', $artista->nombre_artistico);
    }

    /**
     * Test: Artista por defecto no es visible
     */
    public function test_artista_por_defecto_no_visible()
    {
        $user = User::factory()->create();
        $disciplina = Disciplina::factory()->create();

        $artista = Artista::create([
            'user_id'               => $user->id,
            'disciplina_id'         => $disciplina->id,
            'nombre_artistico'      => 'Test Band',
            'localidad'             => 'CABA',
            'slug'                  => 'test-band-xyz',
            'descripcion_actividad' => 'Test',
            'anio_inicio'           => 2024,
            'tiene_formacion'       => false,
            'tiene_documentacion'   => false,
            'acepta_difusion'       => false,
        ]);

        $this->assertFalse($artista->visible);
    }

    /**
     * Test: Relación con User funciona
     */
    public function test_artista_pertenece_a_usuario()
    {
        $user = User::factory()->create();
        $disciplina = Disciplina::factory()->create();

        $artista = Artista::create([
            'user_id'               => $user->id,
            'disciplina_id'         => $disciplina->id,
            'nombre_artistico'      => 'Test',
            'localidad'             => 'CABA',
            'slug'                  => 'test-xyz',
            'descripcion_actividad' => 'Test',
            'anio_inicio'           => 2024,
            'tiene_formacion'       => false,
            'tiene_documentacion'   => false,
            'acepta_difusion'       => false,
        ]);

        // Verificar la relación
        $this->assertEquals($user->id, $artista->user->id);
        $this->assertInstanceOf(User::class, $artista->user);
    }

    /**
     * Test: Relación con Disciplina funciona
     */
    public function test_artista_pertenece_a_disciplina()
    {
        $user = User::factory()->create();
        $disciplina = Disciplina::factory()->create(['nombre' => 'Rock']);

        $artista = Artista::create([
            'user_id'               => $user->id,
            'disciplina_id'         => $disciplina->id,
            'nombre_artistico'      => 'Test',
            'localidad'             => 'CABA',
            'slug'                  => 'test-xyz',
            'descripcion_actividad' => 'Test',
            'anio_inicio'           => 2024,
            'tiene_formacion'       => false,
            'tiene_documentacion'   => false,
            'acepta_difusion'       => false,
        ]);

        $this->assertEquals('Rock', $artista->disciplina->nombre);
        $this->assertEquals($disciplina->id, $artista->disciplina->id);
    }


    /**
     * Test integrantes se guarda como array
     */
    public function test_integrantes_se_almacena_en_bd()
    {
        $user = User::factory()->create();
        $disciplina = Disciplina::factory()->create();

        $artista = Artista::create([
            'user_id'               => $user->id,
            'disciplina_id'         => $disciplina->id,
            'nombre_artistico'      => 'Los Redondos',
            'localidad'             => 'Quilmes',
            'slug'                  => 'test-xyz',
            'descripcion_actividad' => 'Rock',
            'integrantes'           => ['Juan', 'María'],  // Array
            'anio_inicio'           => 1980,
            'tiene_formacion'       => false,
            'tiene_documentacion'   => false,
            'acepta_difusion'       => true,
            'visible'               => false,
        ]);

        // Verificar que se guardó y sigue siendo array
        $this->assertIsArray($artista->integrantes);
        $this->assertCount(2, $artista->integrantes);

        // Verificar que persiste en BD
        $recuperado = Artista::find($artista->id);
        $this->assertIsArray($recuperado->integrantes);
        $this->assertEquals(['Juan', 'María'], $recuperado->integrantes);
    }
}
