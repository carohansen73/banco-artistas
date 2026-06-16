<?php

use App\Http\Controllers\Admin\ArtistaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisciplinaGeneroController;
use App\Http\Controllers\Admin\PlaceholderController;
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

Route::get('/artistas', [ArtistaController::class, 'index'])->name('artistas.index');
Route::patch('/artistas/{artista:id}/visibility', [ArtistaController::class, 'updateVisibility'])
    ->name('artistas.visibility');
Route::get('/artistas/{artista}', [ArtistaController::class, 'show'])->name('artistas.show');
Route::delete('/artistas/{artista}/media/{tipo}/{id}', [ArtistaController::class, 'destroyMedia'])->name('artistas.media.destroy');

Route::get('/disciplinas-generos', [DisciplinaGeneroController::class, 'index'])->name('disciplinas.index');
Route::get('/disciplinas-generos/{disciplina}/edit', [DisciplinaGeneroController::class, 'edit'])->name('disciplinas.edit');
Route::put('/disciplinas-generos/{disciplina}', [DisciplinaGeneroController::class, 'update'])->name('disciplinas.update');
Route::get('/disciplinas-generos/create', [DisciplinaGeneroController::class, 'create'])->name('disciplinas.create');
Route::delete('/disciplinas-generos/{disciplina}', [DisciplinaGeneroController::class, 'destroy'])->name('disciplinas.destroy');


Route::post('generos', [DisciplinaGeneroController::class, 'storeGenero'])->name('generos.store');
Route::delete('generos/{genero}', [DisciplinaGeneroController::class, 'destroyGenero'])->name('generos.destroy');

// TODO:
Route::get('/generos', [PlaceholderController::class, 'show'])->name('generos.index');
Route::get('/usuarios', [PlaceholderController::class, 'show'])->name('usuarios.index');
