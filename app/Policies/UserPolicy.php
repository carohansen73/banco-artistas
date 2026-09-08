<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Habilitar/deshabilitar una cuenta.
     *
     * Nadie puede desactivarse a sí mismo, y sólo un super-admin
     * puede tocar el estado de otro admin o super-admin.
     */
    public function toggleActive(User $authUser, User $target): Response
    {
        if ($authUser->id === $target->id) {
            return Response::deny('No podés desactivarte a vos mismo.');
        }

        if ($target->hasAnyRole(['admin', 'super-admin']) && ! $authUser->hasRole('super-admin')) {
            return Response::deny('Solo un super-admin puede habilitar o deshabilitar a otro administrador.');
        }

        return Response::allow();
    }

    /**
     * Cambiar el rol de un usuario.
     *
     * Sólo un super-admin puede hacerlo, no sobre sí mismo, y no se
     * puede sacar el rol "artista" a alguien que todavía tiene
     * perfiles activos.
     */
    public function updateRole(User $authUser, User $target, string $newRole): Response
    {
        if (! $authUser->hasRole('super-admin')) {
            return Response::deny('No tenés permiso para cambiar roles.');
        }

        if ($authUser->id === $target->id) {
            return Response::deny('No podés cambiar tu propio rol.');
        }

        if ($target->hasRole('artista') && $newRole !== 'artista' && $target->artistas()->exists()) {
            return Response::deny('Este usuario tiene perfiles de artista activos. Pedile que los elimine antes de cambiarle el rol, o hacelo vos desde su perfil.');
        }

        return Response::allow();
    }
}
