<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Evento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    public function index(): View
    {
        $eventos = Evento::with('artistas')
            ->orderBy('fecha_inicio')
            ->paginate(20);

        return view('admin.eventos.index', compact('eventos'));
    }

    public function show(Evento $evento): View
    {
        $evento->load('artistas', 'user');
        return view('admin.eventos.show', compact('evento'));
    }


    public function updateDestacado(Request $request, Evento $evento): JsonResponse
    {
        $validated = $request->validate([
            'destacado' => ['required', 'boolean'],
        ]);

        $evento->update(['destacado' => $validated['destacado']]);

        return response()->json([
            'destacado' => $evento->destacado,
            'message' => $evento->destacado ? 'Evento destacado.' : 'Evento sin destacar.',
        ]);
    }

    public function updateActivo(Request $request, Evento $evento): JsonResponse
    {
        $validated = $request->validate([
            'activo' => ['required', 'boolean'],
        ]);

        $evento->update([
            'activo' => $validated['activo'],
        ]);

        return response()->json([
            'activo' => $evento->activo,
            'message' => $evento->activo
                ? 'El evento ahora es visible al público.'
                : 'El evento ya no es visible al público.',
        ]);
    }

}
