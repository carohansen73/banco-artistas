<?php

namespace Tests\Unit\Policies;

use App\Models\Artista;
use App\Models\Disciplina;
use App\Models\User;
use App\Policies\ArtistaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistaPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ArtistaPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ArtistaPolicy();
    }

    private function crearArtista(User $dueño): Artista
    {
        $disciplina = Disciplina::factory()->create();

        return Artista::create([
            'user_id'               => $dueño->id,
            'disciplina_id'         => $disciplina->id,
            'nombre_artistico'      => 'Test Band',
            'localidad'             => 'CABA',
            'slug'                  => 'test-band-' . uniqid(),
            'descripcion_actividad' => 'Test',
            'anio_inicio'           => 2024,
            'tiene_formacion'       => false,
            'tiene_documentacion'   => false,
            'acepta_difusion'       => false,
        ]);
    }

    public function test_el_dueno_puede_actualizar_su_perfil()
    {
        $user = User::factory()->create();
        $artista = $this->crearArtista($user);

        $this->assertTrue($this->policy->update($user, $artista));
    }

    public function test_otro_usuario_no_puede_actualizar_el_perfil()
    {
        $dueño = User::factory()->create();
        $otro = User::factory()->create();
        $artista = $this->crearArtista($dueño);

        $this->assertFalse($this->policy->update($otro, $artista));
    }

    public function test_el_dueno_puede_eliminar_su_perfil()
    {
        $user = User::factory()->create();
        $artista = $this->crearArtista($user);

        $this->assertTrue($this->policy->delete($user, $artista));
    }

    public function test_otro_usuario_no_puede_eliminar_el_perfil()
    {
        $dueño = User::factory()->create();
        $otro = User::factory()->create();
        $artista = $this->crearArtista($dueño);

        $this->assertFalse($this->policy->delete($otro, $artista));
    }
}
