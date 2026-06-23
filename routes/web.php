<?php

use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// PERFIL DE USUARIO
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Registro de artistas */
Route::middleware(['auth'])->group(function () {
    /* CREAR ARTISTA */
    // Paso 1 - perfil de artista
    Route::get('/artista/crear', [ArtistaController::class, 'create'])->name('artista.create');
    Route::post('/artista/crear', [ArtistaController::class, 'store'])->name('artista.store');
    // Paso 2 - contenido multimedia
    Route::get('/artista/{artista:slug}/contenido', [ArtistaController::class, 'createPaso2'])->name('artista.create.paso2');
    Route::post('/artista/{artista:slug}/contenido', [ArtistaController::class, 'storePaso2'])->name('artista.store.paso2');

    // Edición
    /*falta*/Route::get('/artista/{artista:slug}/editar', [ArtistaController::class, 'edit'])->name('artista.edit');
    /*falta*/Route::put('/artista/{artista:slug}', [ArtistaController::class, 'update'])->name('artista.update');
    // Editar multimedia (paso 2)
    /*falta*/Route::post('/artista/{artista:slug}/media/foto', [ArtistaController::class, 'storeFotos'])->name('artista.store.fotos');
    Route::post('/artista/{artista:slug}/media/links', [ArtistaController::class, 'storeLinks'])->name('artista.store.media');
    Route::post('/artista/{artista:slug}/redes', [ArtistaController::class, 'updateRedes'])->name('artista.update.redes');
    /*falta*/Route::delete('/artista/{artista:slug}/media/{media}', [ArtistaController::class, 'destroyMedia'])->name('artista.destroy.media');
    /*falta*/Route::delete('/artista/{artista:slug}/redes/{red}', [ArtistaController::class, 'destroyRed'])->name('artista.destroy.red');

    // Dashboard del artista (ver sus perfiles)
    /*falta*/Route::get('/mis-perfiles', [ArtistaController::class, 'misPerfiles'])->name('artista.mis-perfiles');
});


/* RUTAS PARA ABM DE EVENTOS */
Route::middleware(['auth'])->group(function () {

    // Eventos
    // TODO:
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('evento.create');
    Route::post('/eventos/crear', [EventoController::class, 'store'])->name('evento.store');
    Route::get('/eventos/{evento:slug}/editar', [EventoController::class, 'edit'])->name('evento.edit');
    Route::put('/eventos/{evento:slug}', [EventoController::class, 'update'])->name('evento.update');
    Route::delete('/eventos/{evento:slug}', [EventoController::class, 'destroy'])->name('evento.destroy');

    // Participación en eventos ajenos
    Route::post('/eventos/{evento:slug}/unirse', [EventoController::class, 'unirse'])->name('evento.unirse'); //falta hacer
    Route::delete('/eventos/{evento:slug}/desvincular/{artista}', [EventoController::class, 'desvincular'])->name('evento.desvincular');

    Route::delete('/eventos/{evento:slug}/salir', [EventoController::class, 'salir'])->name('evento.salir');


});



// RUTAS VISIBLES SIN LOGUEARSE
Route::get('/', [ArtistaController::class, 'home']);
Route::get('/artistas', [ArtistaController::class, 'index'])->name('artistas');
Route::get('/artistas/{artista}', [ArtistaController::class, 'show'])->name('artista.show');



// (Api) Ruta para obtener géneros por disciplina
Route::get('/api/generos/{disciplina}', [DisciplinaController::class, 'generos']);
Route::get('/buscador-de-artistas', [ArtistaController::class, 'searchArtists'])->name('artistas.buscar');


require __DIR__.'/auth.php';
