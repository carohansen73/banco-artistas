<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ArtistaController extends Controller
{
    public function index(): View
    {
        $artistas = Artista::query()
            ->with('user:id,name,lastname,email')
            ->orderBy('nombre_artistico')
            ->paginate(20)
            ->withQueryString();

        return view('admin.artistas.index', compact('artistas'));
    }

    public function updateVisibility(Request $request, Artista $artista): JsonResponse
    {
        $validated = $request->validate([
            'visible' => ['required', 'boolean'],
        ]);

        $artista->update([
            'visible' => $validated['visible'],
        ]);

        return response()->json([
            'visible' => $artista->visible,
            'message' => $artista->visible
                ? 'El perfil ahora es visible al público.'
                : 'El perfil ya no es visible al público.',
        ]);
    }


    public function show(Artista $artista)
    {
        $artista->load(['user', 'disciplina', 'generos', 'redes', 'media']);

        $fotos      = $artista->media->where('tipo', 'foto');
        $videos     = $artista->media->where('tipo', 'video_link');
        $audios     = $artista->media->where('tipo', 'audio_link');


        return view('admin.artistas.show', compact('artista', 'fotos', 'videos', 'audios'));
    }


    /**
     * DELETE /admin/artistas/{artista}/media/{tipo}/{id}
     *
     * Elimina un elemento de media (foto, video, track) de un artista.
     * Las fotos se borran también del storage local.
     *
     * @param  \App\Models\Artista  $artista
     * @param  string               $tipo     foto | video | track
     * @param  int                  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMedia(Artista $artista, string $tipo, int $id): JsonResponse
    {
        // Valida tipo permitido
        if (! in_array($tipo, ['foto', 'video_link', 'audio_link'])) {
            return response()->json(['success' => false, 'message' => 'Tipo no válido.'], 422);
        }

        // Buscar el registro asegurando que pertenece a este artista
        $media = $artista->media()
            ->where('tipo', $tipo)
            ->findOrFail($id);

        // Si es foto, borrar el archivo físico
        if ($tipo === 'foto' && $media->path) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }
}
