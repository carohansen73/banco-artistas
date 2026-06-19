<?php

namespace App\View\Composers;

use Illuminate\View\View;

class EventosComposer
{
    public function compose(View $view): void
    {
        $misArtistas = auth()->check()
        ? auth()->user()->artistas()->where('visible', true)->get()
        : collect();

        $misArtistasIds = $misArtistas->pluck('id')->toArray();

        $view->with('misArtistasIds', $misArtistasIds);
        $view->with('misArtistas', $misArtistas);  // agregar esto
    }
}
