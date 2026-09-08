<?php

namespace Tests\Unit\Policies;

use App\Models\Artista;
use App\Models\Disciplina;
use App\Models\User;
use App\Policies\UserPolicy;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->policy = new UserPolicy();
    }

    private function crearUsuarioConRol(string $rol): User
    {
        $user = User::factory()->create();
        $user->assignRole($rol);

        return $user;
    }

    // --- toggleActive ---

    public function test_admin_no_puede_desactivar_a_otro_admin()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $otroAdmin = $this->crearUsuarioConRol('admin');

        $this->assertFalse($this->policy->toggleActive($admin, $otroAdmin)->allowed());
    }

    public function test_admin_no_puede_desactivar_a_un_super_admin()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $superAdmin = $this->crearUsuarioConRol('super-admin');

        $this->assertFalse($this->policy->toggleActive($admin, $superAdmin)->allowed());
    }

    public function test_admin_puede_desactivar_a_un_artista()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $artista = $this->crearUsuarioConRol('artista');

        $this->assertTrue($this->policy->toggleActive($admin, $artista)->allowed());
    }

    public function test_super_admin_puede_desactivar_a_un_admin()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $admin = $this->crearUsuarioConRol('admin');

        $this->assertTrue($this->policy->toggleActive($superAdmin, $admin)->allowed());
    }

    public function test_super_admin_puede_desactivar_a_otro_super_admin()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $otroSuperAdmin = $this->crearUsuarioConRol('super-admin');

        $this->assertTrue($this->policy->toggleActive($superAdmin, $otroSuperAdmin)->allowed());
    }

    public function test_nadie_puede_desactivarse_a_si_mismo()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');

        $this->assertFalse($this->policy->toggleActive($superAdmin, $superAdmin)->allowed());
    }

    // --- updateRole ---

    public function test_admin_comun_no_puede_cambiar_roles()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $artista = $this->crearUsuarioConRol('artista');

        $this->assertFalse($this->policy->updateRole($admin, $artista, 'admin')->allowed());
    }

    public function test_super_admin_puede_cambiar_el_rol_de_otro_usuario()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $artista = $this->crearUsuarioConRol('artista');

        $this->assertTrue($this->policy->updateRole($superAdmin, $artista, 'admin')->allowed());
    }

    public function test_super_admin_no_puede_cambiar_su_propio_rol()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');

        $this->assertFalse($this->policy->updateRole($superAdmin, $superAdmin, 'admin')->allowed());
    }

    public function test_no_se_puede_sacar_el_rol_artista_si_tiene_perfiles_activos()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $artistaUser = $this->crearUsuarioConRol('artista');
        $disciplina = Disciplina::factory()->create();

        Artista::create([
            'user_id'               => $artistaUser->id,
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

        $this->assertFalse($this->policy->updateRole($superAdmin, $artistaUser, 'admin')->allowed());
    }

    public function test_se_puede_sacar_el_rol_artista_si_no_tiene_perfiles()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $artistaUser = $this->crearUsuarioConRol('artista');

        $this->assertTrue($this->policy->updateRole($superAdmin, $artistaUser, 'admin')->allowed());
    }
}
