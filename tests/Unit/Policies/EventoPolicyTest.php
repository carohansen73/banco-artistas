<?php

namespace Tests\Unit\Policies;

use App\Models\Evento;
use App\Models\User;
use App\Policies\EventoPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventoPolicyTest extends TestCase
{
    use RefreshDatabase;

    private EventoPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new EventoPolicy();
    }

    private function crearEvento(User $creador): Evento
    {
        return Evento::create([
            'nombre'       => 'Festival de prueba',
            'fecha_inicio' => now()->addDays(10),
            'lugar'        => 'Plaza San Martín',
            'user_id'      => $creador->id,
        ]);
    }

    public function test_el_creador_puede_actualizar_su_evento()
    {
        $user = User::factory()->create();
        $evento = $this->crearEvento($user);

        $this->assertTrue($this->policy->update($user, $evento));
    }

    public function test_otro_usuario_no_puede_actualizar_el_evento()
    {
        $creador = User::factory()->create();
        $otro = User::factory()->create();
        $evento = $this->crearEvento($creador);

        $this->assertFalse($this->policy->update($otro, $evento));
    }

    public function test_el_creador_puede_eliminar_su_evento()
    {
        $user = User::factory()->create();
        $evento = $this->crearEvento($user);

        $this->assertTrue($this->policy->delete($user, $evento));
    }

    public function test_otro_usuario_no_puede_eliminar_el_evento()
    {
        $creador = User::factory()->create();
        $otro = User::factory()->create();
        $evento = $this->crearEvento($creador);

        $this->assertFalse($this->policy->delete($otro, $evento));
    }
}
