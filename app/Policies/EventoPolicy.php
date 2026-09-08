<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    /**
     * Sólo el creador del evento puede editarlo.
     */
    public function update(User $user, Evento $evento): bool
    {
        return $user->id === $evento->user_id;
    }

    /**
     * Sólo el creador del evento puede eliminarlo.
     */
    public function delete(User $user, Evento $evento): bool
    {
        return $user->id === $evento->user_id;
    }
}
