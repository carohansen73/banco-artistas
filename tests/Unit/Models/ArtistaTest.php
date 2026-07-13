<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Artista;

/**
 * Unit Tests para el Modelo Artista
 *
 * Testean lógica PURA del modelo, sin tocar la BD.
 * Solo probamos métodos, propiedades, relaciones.
 */
class ArtistaTest extends TestCase
{
    /**
     * Test 1: Verificar que el modelo usa Fillable correctamente
     */
    public function test_artista_tiene_fillable_correcto()
    {
        $artista = new Artista();

        $expected = [
            'user_id',
            'disciplina_id',
            'nombre_artistico',
            'localidad',
            'slug',
            'img_perfil',
            'descripcion_actividad',
            'integrantes',
            'tiene_formacion',
            'detalle_formacion',
            'anio_inicio',
            'tiene_documentacion',
            'acepta_difusion',
            'telefono',
            'domicilio',
            'rol_proyecto',
            'visible',
        ];

        $this->assertEquals($expected, $artista->getFillable());
    }

    /**
     * Test 2: Verificar que los Casts funcionan
     */
    public function test_artista_tiene_casts_correctos()
    {
        $artista = new Artista();
        $casts = $artista->getCasts();

        $this->assertEquals('boolean', $casts['tiene_formacion']);
        $this->assertEquals('boolean', $casts['tiene_documentacion']);
        $this->assertEquals('boolean', $casts['acepta_difusion']);
        $this->assertEquals('boolean', $casts['visible']);
        $this->assertEquals('array', $casts['integrantes']);
    }

    /**
     * Test 3: Verificar que usa slug como route key
     */
    public function test_artista_usa_slug_como_route_key_name()
    {
        $artista = new Artista();
        $this->assertEquals('slug', $artista->getRouteKeyName());
    }

    /**
     * Test 4: Artista tiene los métodos helper
     */
    public function test_artista_tiene_metodos_helper_media()
    {
        $artista = new Artista();

        $this->assertTrue(method_exists($artista, 'fotos'));
        $this->assertTrue(method_exists($artista, 'tracks'));
        $this->assertTrue(method_exists($artista, 'videos'));
    }

    /**
     * Test 5: Artista tiene las relaciones
     */
    public function test_artista_tiene_relaciones()
    {
        $artista = new Artista();

        $this->assertTrue(method_exists($artista, 'user'));
        $this->assertTrue(method_exists($artista, 'disciplina'));
        $this->assertTrue(method_exists($artista, 'generos'));
        $this->assertTrue(method_exists($artista, 'redes'));
        $this->assertTrue(method_exists($artista, 'media'));
        $this->assertTrue(method_exists($artista, 'eventos'));
    }

     /**
     * Test 6: Verificar que tieneDisciplina() es un método callable
     *
     * Es_invocable = se puede llamar como función
     */
    public function test_tieneDisciplina_es_invocable()
    {
        $artista = new Artista();
        $this->assertTrue(is_callable([$artista, 'tieneDisciplina']));
    }

     /**
     * Test 7: Tabla de la BD debe ser 'artistas' (plural)
     */
    public function test_tabla_es_artistas()
    {
        $artista = new Artista();
        $this->assertEquals('artistas', $artista->getTable());
    }
}
