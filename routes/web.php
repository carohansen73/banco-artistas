<?php

use App\Http\Controllers\ArtistaController;
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
    // Paso 1 - perfil de artista
    Route::get('/artista/crear', [ArtistaController::class, 'create'])->name('artista.create');
    Route::post('/artista/crear', [ArtistaController::class, 'store'])->name('artista.store');

    // Paso 2 - contenido
    Route::get('/artista/{artista}/contenido', [ArtistaController::class, 'createPaso2'])->name('artista.create.paso2');
    Route::post('/artista/{artista}/contenido', [ArtistaController::class, 'storePaso2'])->name('artista.store.paso2');

    // Edición
    Route::get('/artista/{slug}/editar', [ArtistaController::class, 'edit'])->name('artista.edit');
    Route::put('/artista/{slug}', [ArtistaController::class, 'update'])->name('artista.update');
});



// RUTAS VISIBLES SIN LOGUEARSE
Route::get('/', [ArtistaController::class, 'home']);
Route::get('/artistas', [ArtistaController::class, 'index'])->name('artistas');
Route::get('/artistas/{artista}', [ArtistaController::class, 'show'])->name('artista.show');



// (Api) Ruta para obtener géneros por disciplina
Route::get('/api/generos/{disciplina}', function (App\Models\Disciplina $disciplina) {
        return $disciplina->generos()->orderBy('nombre')->get(['id', 'nombre']);
    });
Route::get('/buscador-de-artistas', [ArtistaController::class, 'searchArtists'])->name('artistas.buscar');


require __DIR__.'/auth.php';
