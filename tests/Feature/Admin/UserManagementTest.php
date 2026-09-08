<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica, de punta a punta (ruta -> middleware -> Policy), la jerarquía
 * admin / super-admin en el panel de administración de usuarios.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearUsuarioConRol(string $rol): User
    {
        $user = User::factory()->create();
        $user->assignRole($rol);

        return $user;
    }

    public function test_admin_comun_no_puede_desactivar_a_un_super_admin()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $superAdmin = $this->crearUsuarioConRol('super-admin');

        $response = $this->actingAs($admin)
            ->patch(route('admin.usuarios.toggle-active', $superAdmin));

        $response->assertForbidden();
        $this->assertTrue($superAdmin->fresh()->is_active);
    }

    public function test_super_admin_puede_desactivar_a_un_admin()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $admin = $this->crearUsuarioConRol('admin');

        $response = $this->actingAs($superAdmin)
            ->patch(route('admin.usuarios.toggle-active', $admin));

        $response->assertOk();
        $this->assertFalse($admin->fresh()->is_active);
    }

    public function test_admin_comun_puede_desactivar_a_un_artista()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $artista = $this->crearUsuarioConRol('artista');

        $response = $this->actingAs($admin)
            ->patch(route('admin.usuarios.toggle-active', $artista));

        $response->assertOk();
        $this->assertFalse($artista->fresh()->is_active);
    }

    public function test_admin_comun_no_puede_cambiar_roles()
    {
        $admin = $this->crearUsuarioConRol('admin');
        $artista = $this->crearUsuarioConRol('artista');

        $response = $this->actingAs($admin)
            ->patch(route('admin.usuarios.update-role', $artista), ['role' => 'admin']);

        $response->assertForbidden();
        $this->assertTrue($artista->fresh()->hasRole('artista'));
    }

    public function test_super_admin_puede_cambiar_el_rol_de_un_artista_sin_perfiles()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $artista = $this->crearUsuarioConRol('artista');

        $response = $this->actingAs($superAdmin)
            ->patch(route('admin.usuarios.update-role', $artista), ['role' => 'admin']);

        $response->assertOk();
        $this->assertTrue($artista->fresh()->hasRole('admin'));
    }

    public function test_usuario_desactivado_es_desconectado_en_el_siguiente_request()
    {
        $superAdmin = $this->crearUsuarioConRol('super-admin');
        $admin = $this->crearUsuarioConRol('admin');

        $admin->update(['is_active' => false]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
