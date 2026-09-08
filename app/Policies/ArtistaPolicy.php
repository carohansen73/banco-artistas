<?php

namespace App\Policies;

use App\Models\Artista;
use App\Models\User;

class ArtistaPolicy
{
    /**
     * Sólo el dueño del perfil puede editar su contenido
     * (info general, multimedia, redes).
     */
    public function update(User $user, Artista $artista): bool
    {
        return $user->id === $artista->user_id;
    }

    /**
     * Sólo el dueño del perfil puede eliminarlo.
     */
    public function delete(User $user, Artista $artista): bool
    {
        return $user->id === $artista->user_id;
    }
}
