<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Artista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador encargado de la gestión de eventos culturales.
 *
 * Administra la creación, edición, visualización y eliminación
 * de eventos publicados por los artistas.
 *
 * Un evento puede estar asociado a uno o varios perfiles
 * artísticos pertenecientes al usuario creador. Además,
 * otros artistas pueden incorporarse posteriormente como
 * participantes sin convertirse en propietarios del evento.
 */
class EventoController extends Controller
{


   /**
     * Muestra el formulario de creación de un evento.
     *
     * Obtiene todos los perfiles artísticos visibles del usuario
     * autenticado para que pueda seleccionar cuáles participarán
     * del evento. Si el usuario aún no posee perfiles aprobados,
     * es redirigido al formulario de creación de artistas.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     * Crea un nuevo evento.
     *
     * Valida la información enviada desde el formulario, verifica
     * que los perfiles seleccionados pertenezcan al usuario,
     * almacena la imagen de portada (si fue enviada) y registra
     * el evento junto con los artistas participantes.
     *
     * @param  StoreEventoRequest  $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Muestra el detalle público de un evento.
     *
     * Carga la información del evento junto con los artistas
     * participantes para ser visualizado por cualquier visitante.
     *
     * @param  Evento  $evento
     * @return \Illuminate\View\View
     */
    public function show(Evento $evento)
    {
        $evento->load('artistas');
        return view('eventos.show', compact('evento'));
    }

    /**
     * Muestra el formulario de edición de un evento.
     *
     * Sólo el creador del evento puede acceder a esta vista.
     * Carga los perfiles artísticos del usuario y marca aquellos
     * que actualmente participan del evento.
     *
     * @param  Evento  $evento
     * @return \Illuminate\View\View
     */
    public function edit(Evento $evento)
    {
        $this->autorizarCreador($evento);

        $artistas = auth()->user()->artistas()->where('visible', true)->get();
        $artistasSeleccionados = $evento->artistas->pluck('id')->toArray();

        return view('eventos.edit', compact('evento', 'artistas', 'artistasSeleccionados'));
    }

    /**
     * Actualiza la información de un evento existente.
     *
     * Verifica que el usuario sea el creador del evento, valida
     * los datos enviados, reemplaza la imagen de portada cuando
     * corresponde y sincroniza únicamente los perfiles artísticos
     * pertenecientes al usuario autenticado.
     *
     * Los artistas asociados por otros usuarios no son modificados.
     *
     * @param  UpdateEventoRequest  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEventoRequest $request, Evento $evento)
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
     * Elimina un evento.
     *
     * Sólo el creador del evento puede realizar esta acción.
     * Si el evento posee una imagen de portada almacenada,
     * también elimina el archivo físico del servidor.
     *
     * @param  Evento  $evento
     * @return \Illuminate\Http\RedirectResponse
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
     * Permite al usuario sumar uno o más de sus perfiles
     * artísticos como participantes de un evento existente.
     *
     * Se valida que los perfiles seleccionados pertenezcan
     * al usuario autenticado y sólo se agregan aquellos que
     * todavía no estén asociados al evento.
     *
     * @param  Request  $request
     * @param  Evento   $evento
     * @return \Illuminate\Http\RedirectResponse
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
     * Desvincula un perfil artístico de un evento.
     *
     * Verifica que el perfil pertenezca al usuario autenticado
     * antes de eliminar su participación del evento.
     *
     * @param  Evento   $evento
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
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


    /**
     * Permite abandonar un evento.
     *
     * Si se indican perfiles específicos, sólo se desvinculan
     * esos artistas. En caso contrario, se eliminan del evento
     * todos los perfiles artísticos pertenecientes al usuario.
     *
     * @param  Request  $request
     * @param  Evento   $evento
     * @return \Illuminate\Http\RedirectResponse
     */
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

    // --- Helper privado ---

    /**
     * Verifica que el usuario autenticado sea el creador del evento.
     *
     * Si el evento pertenece a otro usuario, se aborta la petición
     * devolviendo un error HTTP 403 (Forbidden).
     *
     * @param  Evento  $evento
     * @return void
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
