<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Disciplina;
use App\Models\Genero;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class DisciplinaGeneroController extends Controller
{
    public function index(): View
    {
       $disciplinas = Disciplina::withCount('artistas')
        ->with('generos')
        ->orderBy('nombre')
        ->paginate(20);

        return view('admin.disciplinas-generos.index2', compact('disciplinas'));
    }

    public function create()
    {
        return view('admin.disciplinas.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100|unique:disciplinas,nombre']);
        Disciplina::create(['nombre' => $request->nombre]);
        return redirect()->route('admin.disciplinas.index')->with('success', 'Disciplina creada.');
    }

    public function edit(Disciplina $disciplina)
    {
        $disciplina->load(['generos' => fn($q) => $q->withCount('artistas')]);
        return view('admin.disciplinas-generos.edit', compact('disciplina'));
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $request->validate(['nombre' => 'required|string|max:100|unique:disciplinas,nombre,' . $disciplina->id]);
        $disciplina->update(['nombre' => $request->nombre]);
        return back()->with('success', 'Disciplina actualizada.');
    }

    public function destroy(Disciplina $disciplina)
    {
        if ($disciplina->artistas()->exists()) {
            return back()->with('error', 'No se puede eliminar, hay artistas con esta disciplina.');
        }
        $disciplina->delete();
        return redirect()->route('admin.disciplinas.index')->with('success', 'Disciplina eliminada.');
    }

    /* ----------------------------  GENERO  -----------------------------*/

    public function storeGenero(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:100',
            'disciplina_id' => 'required|exists:disciplinas,id',
        ]);

        Genero::create([
            'nombre' => $request->nombre,
            'disciplina_id' => $request->disciplina_id,
        ]);

        return back()->with('success', 'Género agregado.');
    }

    public function destroyGenero(Genero $genero)
    {
        if ($genero->artistas()->exists()) {
            return back()->with('error', 'No se puede eliminar, hay artistas con este género.');
        }
        $genero->delete();
        return back()->with('success', 'Género eliminado.');
    }
}
