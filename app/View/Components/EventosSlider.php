<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventosSlider extends Component
{

    public $eventos;
    public $artistaActual;
    public $modo;
    /**
     * Create a new component instance.
     */
    public function __construct($eventos, $artistaActual = null, $modo = 'publico')
    {
        $this->eventos = $eventos;
        $this->artistaActual = $artistaActual;
        $this->modo = $modo;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eventos-slider');
    }
}
