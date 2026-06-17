<?php

return [

  'name' => 'Panel de administración',

  /*
  |--------------------------------------------------------------------------
  | Menú lateral del panel
  |--------------------------------------------------------------------------
  |
  | route: nombre de ruta Laravel
  | active: patrón para request()->routeIs()
  |
  */
  'menu' => [
    [
      'label' => 'Panel',
      'route' => 'admin.dashboard',
      'active' => 'admin.dashboard',
      'icon' => 'home',
    ],
    [
      'label' => 'Artistas',
      'route' => 'admin.artistas.index',
      'active' => 'admin.artistas.*',
      'icon' => 'users',
    ],
    // [
    //   'label' => 'Eventos',
    //   'route' => 'admin.eventos.index',
    //   'active' => 'admin.eventos.*',
    //   'icon' => 'calendar',
    // ],
    [
      'label' => 'Disciplinas',
      'route' => 'admin.disciplinas.index',
      'active' => 'admin.disciplinas.*',
      'icon' => 'tag',
    ],
    [
      'label' => 'Usuarios',
      'route' => 'admin.usuarios.index',
      'active' => 'admin.usuarios.*',
      'icon' => 'user-group',
    ],
  ],

];
