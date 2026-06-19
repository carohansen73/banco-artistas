<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Artista;
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
    public function store(StoreEventoRequest $request)
    {
        $artistas = auth()->user()->artistas()->where('visible', true)->get();

        if ($artistas->isEmpty()) {
            abort(403);
        }

        $data = $request->validated();

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

        $evento->artistas()->attach($data['artistas_ids']);

        return redirect()->route('artista.mis-perfiles')
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
    public function update(StoreEventoRequest $request, Evento $evento)
    {
        $this->autorizarCreador($evento);

        $artistas = auth()->user()->artistas()->where('visible', true)->get();
        $idsDelUser = $artistas->pluck('id')->toArray();

        $data = $request->validated();

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

        $evento->artistas()->attach(($data['artistas_ids']));

        return redirect()->route('artista.mis-perfiles')
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

        return redirect()->route('artista.mis-perfiles')
            ->with('success', 'Evento eliminado');
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
        ]);

        $idsDelUser = $artistas->pluck('id')->toArray();

        if (array_diff($data['artistas_ids'], $idsDelUser)) {
            abort(403, 'Uno o más perfiles seleccionados no te pertenecen.');
        }

        // attach solo los que NO están ya en la pivot (evita duplicados por el unique)
        $yaAsociados = $evento->artistas()->pluck('artistas.id')->toArray();
        $nuevos = array_diff($data['artistas_ids'], $yaAsociados);

        if (!empty($nuevos)) {
            $evento->artistas()->attach($nuevos);
        }

        return back()->with('success', 'Te sumaste al evento!');
    }

    /**
     * Un artista se retira de un evento que no creó.
     */
    public function desvincular(Evento $evento, Artista $artista)
    {
        // Verificar que el artista le pertenece al usuario
        if ($artista->user_id !== auth()->id()) {
            abort(403);
        }

        $evento->artistas()->detach($artista->id);

       return back()->with('success', 'Saliste del evento correctamente.');
    }


    // Salir del evento
    public function salir(Request $request, Evento $evento)
    {
        $idsDelUser = auth()->user()->artistas->pluck('id')->toArray();

        // Si vienen ids específicos, validarlos; si no, desvincula todos
        $idsASalir = $request->input('artistas_ids', $idsDelUser);

        // Verificar que los ids pertenecen al user
        if (array_diff($idsASalir, $idsDelUser)) {
            abort(403);
        }

        $evento->artistas()->detach($idsASalir);

        return back()->with('success', 'Saliste del evento.');
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
