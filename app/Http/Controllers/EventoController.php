<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{


    /**
     * Muestra el form para crear un evento.
     * Si el user tiene múltiples artistas, los pasa para que elija con cuáles participa.
     */
    public function create()
    {
        $artistas = auth()->user()->artistas()->where('visible', true)->get();

        if ($artistas->isEmpty()) {
            return redirect()->route('artista.create')
                ->with('error', 'Necesitás tener un perfil de artista para crear eventos.');
        }

        return view('eventos.create', compact('artistas'));
    }

    /**
     * Guarda el nuevo evento y asocia los artistas participantes.
     */
    public function store(Request $request)
    {
        $artistas = auth()->user()->artistas()->where('visible', true)->get();

        if ($artistas->isEmpty()) {
            abort(403);
        }

        $data = $request->validate([
            'nombre'                  => 'required|string|max:255',
            'descripcion'             => 'nullable|string',
            'fecha_inicio'            => 'required|date',
            'fecha_fin'               => 'nullable|date|after_or_equal:fecha_inicio',
            'lugar'                   => 'required|string|max:255',
            'direccion'               => 'nullable|string|max:255',
            'ciudad'                  => 'nullable|string|max:100',
            'imagen_portada'          => 'nullable|image|max:2048',
            'link_entradas'           => 'nullable|url|max:255',
            'link_externo'            => 'nullable|url|max:255',
            'artistas_ids'            => 'required|array|min:1',
            'artistas_ids.*'          => 'integer|exists:artistas,id',
            'descripcion_participacion' => 'nullable|string',
        ]);

        // Verificar que los artistas_ids seleccionados pertenecen al user
        $idsDelUser = $artistas->pluck('id')->toArray();
        $idsSeleccionados = $data['artistas_ids'];

        if (array_diff($idsSeleccionados, $idsDelUser)) {
            abort(403, 'Uno o más perfiles seleccionados no te pertenecen.');
        }

        // Imagen
        if ($request->hasFile('imagen_portada')) {
            $data['imagen_portada'] = $request->file('imagen_portada')
                ->store('eventos/portadas', 'public');
        }

        // Crear el evento
        $evento = Evento::create([
            'nombre'          => $data['nombre'],
            'descripcion'     => $data['descripcion'] ?? null,
            'fecha_inicio'    => $data['fecha_inicio'],
            'fecha_fin'       => $data['fecha_fin'] ?? null,
            'lugar'           => $data['lugar'],
            'direccion'       => $data['direccion'] ?? null,
            'ciudad'          => $data['ciudad'] ?? null,
            'imagen_portada'  => $data['imagen_portada'] ?? null,
            'link_entradas'   => $data['link_entradas'] ?? null,
            'link_externo'    => $data['link_externo'] ?? null,
            'user_id'         => auth()->id(),
        ]);

        // Asociar artistas en la pivot
        $pivot = [];
        foreach ($idsSeleccionados as $artistaId) {
            $pivot[$artistaId] = [
                'descripcion_participacion' => $data['descripcion_participacion'] ?? null,
            ];
        }
        $evento->artistas()->attach($pivot);

        return redirect()->route('evento.show', $evento)
            ->with('success', '¡Evento creado con éxito!');
    }

    /**
     * Vista pública del evento.
     */
    public function show(Evento $evento)
    {
        $evento->load('artistas');
        return view('eventos.show', compact('evento'));
    }

    /**
     * Form de edición — solo el creador del evento puede editarlo.
     */
    public function edit(Evento $evento)
    {
        $this->autorizarCreador($evento);

        $artistas = auth()->user()->artistas()->where('visible', true)->get();
        $artistasSeleccionados = $evento->artistas->pluck('id')->toArray();

        return view('eventos.edit', compact('evento', 'artistas', 'artistasSeleccionados'));
    }

    /**
     * Actualiza el evento.
     */
    public function update(Request $request, Evento $evento)
    {
        $this->autorizarCreador($evento);

        $artistas = auth()->user()->artistas()->where('visible', true)->get();
        $idsDelUser = $artistas->pluck('id')->toArray();

        $data = $request->validate([
            'nombre'                  => 'required|string|max:255',
            'descripcion'             => 'nullable|string',
            'fecha_inicio'            => 'required|date',
            'fecha_fin'               => 'nullable|date|after_or_equal:fecha_inicio',
            'lugar'                   => 'required|string|max:255',
            'direccion'               => 'nullable|string|max:255',
            'ciudad'                  => 'nullable|string|max:100',
            'imagen_portada'          => 'nullable|image|max:2048',
            'link_entradas'           => 'nullable|url|max:255',
            'link_externo'            => 'nullable|url|max:255',
            'artistas_ids'            => 'required|array|min:1',
            'artistas_ids.*'          => 'integer|exists:artistas,id',
            'descripcion_participacion' => 'nullable|string',
        ]);

        if (array_diff($data['artistas_ids'], $idsDelUser)) {
            abort(403, 'Uno o más perfiles seleccionados no te pertenecen.');
        }

        // Imagen: reemplazar si viene nueva
        if ($request->hasFile('imagen_portada')) {
            if ($evento->imagen_portada) {
                Storage::disk('public')->delete($evento->imagen_portada);
            }
            $data['imagen_portada'] = $request->file('imagen_portada')
                ->store('eventos/portadas', 'public');
        }

        $evento->update([
            'nombre'         => $data['nombre'],
            'descripcion'    => $data['descripcion'] ?? null,
            'fecha_inicio'   => $data['fecha_inicio'],
            'fecha_fin'      => $data['fecha_fin'] ?? null,
            'lugar'          => $data['lugar'],
            'direccion'      => $data['direccion'] ?? null,
            'ciudad'         => $data['ciudad'] ?? null,
            'imagen_portada' => $data['imagen_portada'] ?? $evento->imagen_portada,
            'link_entradas'  => $data['link_entradas'] ?? null,
            'link_externo'   => $data['link_externo'] ?? null,
        ]);

        // Sincronizar solo los artistas del propio user (no tocar los de otros)
        // Primero desvincula los del user, luego re-adjunta los seleccionados
        $evento->artistas()->detach($idsDelUser);

        $pivot = [];
        foreach ($data['artistas_ids'] as $artistaId) {
            $pivot[$artistaId] = [
                'descripcion_participacion' => $data['descripcion_participacion'] ?? null,
            ];
        }
        $evento->artistas()->attach($pivot);

        return redirect()->route('evento.show', $evento)
            ->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Elimina el evento — solo el creador.
     */
    public function destroy(Evento $evento)
    {
        $this->autorizarCreador($evento);

        if ($evento->imagen_portada) {
            Storage::disk('public')->delete($evento->imagen_portada);
        }

        $evento->delete();

        return redirect()->back()->with('success', 'Evento eliminado.');
    }

    /**
     * Un artista se une a un evento que no creó.
     */
    public function unirse(Request $request, Evento $evento)
    {
        $artistas = auth()->user()->artistas()->where('visible', true)->get();

        if ($artistas->isEmpty()) {
            abort(403);
        }

        $data = $request->validate([
            'artistas_ids'              => 'required|array|min:1',
            'artistas_ids.*'            => 'integer|exists:artistas,id',
            'descripcion_participacion' => 'nullable|string',
        ]);

        $idsDelUser = $artistas->pluck('id')->toArray();

        if (array_diff($data['artistas_ids'], $idsDelUser)) {
            abort(403, 'Uno o más perfiles seleccionados no te pertenecen.');
        }

        // attach solo los que NO están ya en la pivot (evita duplicados por el unique)
        $yaAsociados = $evento->artistas()->pluck('artistas.id')->toArray();
        $nuevos = array_diff($data['artistas_ids'], $yaAsociados);

        $pivot = [];
        foreach ($nuevos as $artistaId) {
            $pivot[$artistaId] = [
                'descripcion_participacion' => $data['descripcion_participacion'] ?? null,
            ];
        }

        if (!empty($pivot)) {
            $evento->artistas()->attach($pivot);
        }

        return redirect()->route('evento.show', $evento)
            ->with('success', '¡Te sumaste al evento!');
    }

    /**
     * Un artista se retira de un evento que no creó.
     */
    public function salir(Evento $evento)
    {
        $idsDelUser = auth()->user()->artistas()->pluck('id')->toArray();

        $evento->artistas()->detach($idsDelUser);

        return redirect()->route('evento.show', $evento)
            ->with('success', 'Saliste del evento.');
    }

    // --- Helpers privados ---

    /**
     * Autoriza solo al creador del evento.
     */
    private function autorizarCreador(Evento $evento): void
    {
        if ($evento->user_id !== auth()->id()) {
            abort(403, 'No tenés permiso para modificar este evento.');
        }
    }
















    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


}
