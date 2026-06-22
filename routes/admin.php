<?php

use App\Http\Controllers\Admin\ArtistaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisciplinaGeneroController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\PlaceholderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Panel de administración
|--------------------------------------------------------------------------
|
| Middleware: auth + admin (EnsureUserIsAdmin)
| Sin middleware 'verified' por el momento (ver bootstrap / grupo).
|
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/* ARTISTAS */
Route::get('/artistas', [ArtistaController::class, 'index'])->name('artistas.index');
Route::patch('/artistas/{artista:id}/visibility', [ArtistaController::class, 'updateVisibility'])
    ->name('artistas.visibility');
Route::get('/artistas/{artista}', [ArtistaController::class, 'show'])->name('artistas.show');
Route::delete('/artistas/{artista}/media/{tipo}/{id}', [ArtistaController::class, 'destroyMedia'])->name('artistas.media.destroy');

/* DISCIPLINAS */
Route::get('/disciplinas-generos', [DisciplinaGeneroController::class, 'index'])->name('disciplinas.index');
Route::get('/disciplinas-generos/{disciplina}/edit', [DisciplinaGeneroController::class, 'edit'])->name('disciplinas.edit');
Route::put('/disciplinas-generos/{disciplina}', [DisciplinaGeneroController::class, 'update'])->name('disciplinas.update');
Route::get('/disciplinas-generos/create', [DisciplinaGeneroController::class, 'create'])->name('disciplinas.create');
Route::delete('/disciplinas-generos/{disciplina}', [DisciplinaGeneroController::class, 'destroy'])->name('disciplinas.destroy');

/* GENEROS */
Route::post('generos', [DisciplinaGeneroController::class, 'storeGenero'])->name('generos.store');
Route::delete('generos/{genero}', [DisciplinaGeneroController::class, 'destroyGenero'])->name('generos.destroy');


/* USUARIOS */
// Route::get('/usuarios', [PlaceholderController::class, 'show'])->name('usuarios.index');
Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
// Habilitar/deshabilitar usuario
Route::patch('usuarios/{user}/toggle-active', [UserController::class, 'toggleActive'])
    ->name('usuarios.toggle-active');

/* EVENTOS */
Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
Route::patch('/eventos/{evento}/activo', [EventoController::class, 'updateActivo'])
    ->name('eventos.activo');
Route::patch('/eventos/{evento}/destacado', [EventoController::class, 'updateDestacado'])
    ->name('eventos.destacado');
