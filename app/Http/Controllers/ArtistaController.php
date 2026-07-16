<?php

namespace App\Http\Controllers;

use App\Models\Artista;
use App\Http\Requests\StoreArtistaRequest;
use App\Http\Requests\UpdateArtistaRequest;
use App\Mail\NuevaInscripcionAdmin;
use App\Models\ArtistaRedes;
use App\Models\Disciplina;
use App\Models\Evento;
use App\Models\Genero;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Controlador encargado de la gestión de perfiles artísticos.
 *
 * Administra tanto las vistas públicas del catálogo de artistas
 * como el proceso de inscripción, edición y administración de
 * perfiles por parte de los usuarios autenticados.
 *
 * El proceso de inscripción se realiza en dos pasos:
 *  - Paso 1: creación del perfil con la información general.
 *  - Paso 2: carga de redes sociales y contenido multimedia.
 *
 * La edición del perfil se encuentra desacoplada en distintos
 * métodos para permitir la actualización independiente de la
 * información general, las fotografías, los enlaces multimedia
 * y las redes sociales.
 */

class ArtistaController extends Controller
{
    /* --------------------------------------------------------------------------
    |  PÁGINAS PÚBLICAS
    * -------------------------------------------------------------------------- */

    /**
     *  Muestra la página principal del sitio.
     *
     * Carga los últimos artistas visibles y los eventos
     * destacados que se encuentran vigentes.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $artistas = Artista::where('visible', 1)->orderBy('created_at', 'desc')->take(12)->get();
        $eventos = Evento::with('artistas')
            ->activos()
            ->destacados()
            ->vigentes()
            ->orderBy('fecha_inicio')
            ->take(10)
            ->get();
        return view('public.home', compact('eventos', 'artistas'));
    }

    /**
    * Muestra el catálogo público de artistas.
    *
    * Obtiene todos los perfiles visibles junto con las disciplinas
    * y géneros disponibles para construir los filtros de búsqueda.
    * Además prepara una versión simplificada de los datos para
    * ser utilizada por JavaScript.
    *
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $artistas = Artista::where('visible', 1)
            ->with('disciplina', 'generos')
            ->orderBy('nombre_artistico', 'desc')
            ->get();
        $disciplinas = Disciplina::orderBy('nombre')->get();
        $generos     = Genero::orderBy('nombre')->get();

        //
        $artistasJs = $artistas->map(fn ($a) => [
            'slug'             => $a->slug,
            'nombre_artistico' => $a->nombre_artistico,
            'localidad'        => $a->localidad,
            'disciplina'       => $a->disciplina?->nombre,
            'generos'          => $a->generos->pluck('nombre'),
            'img_perfil'       => $a->img_perfil
                ? asset('storage/' . $a->img_perfil)
                : asset('img/default.jpg'),
        ]);

        return view('public.artistas.index', compact('artistas', 'disciplinas', 'generos', 'artistasJs'));
    }


    /**
     * Muestra el perfil público de un artista.
     *
     * Carga toda la información asociada al perfil, incluyendo
     * disciplina, géneros, redes sociales, contenido multimedia
     * y los eventos vigentes en los que participa.
     *
     * @param  Artista  $artista
     * @return \Illuminate\View\View
     */
    public function show(Artista $artista)
    {
        $artista->load(['disciplina', 'generos', 'redes', 'media', 'tracks']);

        $fotos      = $artista->media->where('tipo', 'foto');
        $videos     = $artista->media->where('tipo', 'video_link');
        $audios     = $artista->media->where('tipo', 'audio_link');

        $eventos = $artista->eventos()
            ->activos()
            ->vigentes()
            ->orderBy('fecha_inicio')
            ->get();


        return view('public.artistas.show', compact('artista', 'fotos', 'videos', 'audios', 'eventos'));
    }


    /* --------------------------------------------------------------------------
    |  REGISTRO DE ARTISTAS
    * -------------------------------------------------------------------------- */

    /**
     * Muestra el primer paso del formulario de inscripción
     * de un nuevo perfil artístico.
     *
     * Carga las disciplinas habilitadas para que el usuario
     * complete la información general del proyecto.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $disciplinas = Disciplina::where('pendiente_revision', false)->orderBy('nombre')->get();
        return view('artista.create', compact('disciplinas'));
    }

    /**
     * Guarda un nuevo perfil artístico (Procesa el primer paso).
     *
     * Valida los datos enviados por el formulario, procesa la
     * imagen de perfil, crea el registro inicial del artista
     * y lo deja pendiente de aprobación. Envía
     * una notificación al equipo de Cultura para su revisión
     * y redirige al segundo paso de la inscripción.
     *
     * @param  StoreArtistaRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreArtistaRequest $request)
    {
        $data = $request->validated();

        // Evita que un mismo usuario registre más de un perfil con el mismo nombre artístico.
        $existe = Artista::where('user_id', Auth::id())
            ->whereRaw('LOWER(nombre_artistico) = ?', [strtolower($data['nombre_artistico'])])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['nombre_artistico' => 'Ya tenés una banda registrada con ese nombre.']);
        }

        // Convierte la imagen a WebP y la redimensiona para reducir
        // el peso de almacenamiento sin perder calidad visual.
        if ($request->hasFile('img_perfil')) {
            $file = $request->file('img_perfil');
            $filename = Str::random(20) . '.webp';

            $image = Image::read($file)
                ->scaleDown(width: 800) // nunca más ancho que 800px, mantiene proporción
                ->toWebp(quality: 75);  // convierte a webp, formato mucho más liviano

            Storage::disk('public')->put('artistas/' . $filename, (string) $image);
            $data['img_perfil'] = 'artistas/' . $filename;
        }

        // Elimina integrantes vacíos enviados por el formulario
        // y normaliza el arreglo antes de guardar.
        $data['integrantes'] = array_values(
            array_filter($data['integrantes'] ?? [], fn($v) => trim($v) !== '')
        ) ?: null;
        $data['user_id'] = Auth::id();
        $data['slug']    = Str::slug($data['nombre_artistico']) . '-' . uniqid();
        // Los nuevos perfiles quedan ocultos hasta ser aprobados
        // por un administrador del área de Cultura.
        $data['visible'] = false;

        $artista = Artista::create($data);

        // Géneros (pivot)
        if ($request->filled('generos')) {
            $artista->generos()->sync($request->generos);
        }

        // Notifica al equipo de Cultura para que revise
        // y apruebe el nuevo perfil artístico.
        Mail::to(config('mail.from.address'))->send(new NuevaInscripcionAdmin($artista)); // Mailable al mail del .env

        // Redirigir al apso 2
        return redirect()
            ->route('artista.create.paso2', $artista->slug)
            ->with('success', '¡Perfil creado! Ahora completá tus redes y contenido.');
    }


    /**
     * Muestra el segundo paso de la inscripción.
     *
     * Permite completar la información complementaria del perfil,
     * incluyendo redes sociales, fotografías, videos y audios.
     *
     * Sólo el propietario del perfil puede acceder a esta sección.
     *
     * @param  Artista  $artista
     * @return \Illuminate\View\View
     */
    public function createPaso2(Artista $artista)
    {
        // Impide que un usuario acceda al paso 2
        // de un perfil que no le pertenece.
        abort_if($artista->user_id !== Auth::id(), 403);

        $redes = $artista->redes->keyBy('plataforma');
        $redesConfig = config('redes');

        return view('artista.create-paso2', compact('artista', 'redes', 'redesConfig'));
    }


    /**
     * Procesa el segundo paso de la inscripción.
     *
     * Guarda las redes sociales y el contenido multimedia asociado
     * al perfil artístico. Una vez completado el proceso, el perfil
     * queda listo para ser revisado por el equipo de Cultura.
     *
     * @param  Request  $request
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePaso2(Request $request, Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $request->validate([
            'redes'             => 'nullable|array',
            'redes.*'           => 'nullable|url|max:255',
            'fotos.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'tracks.*'          => 'nullable|url|max:255',
            'tracks_titulo.*'   => 'nullable|string|max:255',
            'videos.*'          => 'nullable|url|max:255',
            'videos_titulo.*'   => 'nullable|string|max:255',
        ]);

        // Guarda o actualiza las redes sociales del artista.
        if ($request->filled('redes')) {
            foreach ($request->redes as $plataforma => $url) {
                if (empty($url)) continue;

                $artista->redes()->updateOrCreate(
                    ['plataforma' => $plataforma],
                    ['url' => $url]
                );
            }
        }

        // Optimiza imagenes antes de almacenarlas:
        // - limita el ancho máximo a 800 px
        // - convierte el archivo a WebP
        // - reduce el tamaño para mejorar el rendimiento del sitio
        if ($request->hasFile('fotos')) {
            $orden = $artista->fotos()->max('orden') ?? 0;
            $fotos = $request->file('fotos');

            foreach ((array) $fotos as $foto) {
                if (! $foto) {
                    continue;
                }

                $filename = Str::random(20) . '.webp';
                $image = Image::read($foto)
                ->scaleDown(width: 800) // nunca más ancho que 800px, mantiene proporción
                ->toWebp(quality: 75);  // convierte a webp, formato mucho más liviano
                Storage::disk('public')->put('artistas/fotos/' . $filename, (string) $image);

                $artista->media()->create([
                    'tipo'  => 'foto',
                    'url'   => 'artistas/fotos/' . $filename,
                    'orden' => ++$orden,
                ]);
            }
        }

        // Registra los enlaces de Spotify respetando
        // el orden definido por el usuario.
        if ($request->filled('tracks')) {
            $orden = $artista->tracks()->max('orden') ?? 0;
            foreach ($request->tracks as $i => $url) {
                if (empty($url)) continue;
                $artista->media()->create([
                    'tipo'   => 'audio_link',
                    'url'    => $url,
                    'titulo' => $request->tracks_titulo[$i] ?? null,
                    'orden'  => ++$orden,
                ]);
            }
        }

        // Registra los enlaces de YouTube respetando
        // el orden definido por el usuario.
        if ($request->filled('videos')) {
            $orden = $artista->videos()->max('orden') ?? 0;
            foreach ($request->videos as $i => $url) {
                if (empty($url)) continue;
                $artista->media()->create([
                    'tipo'   => 'video_link',
                    'url'    => $url,
                    'titulo' => $request->videos_titulo[$i] ?? null,
                    'orden'  => ++$orden,
                ]);
            }
        }

        return redirect()->route('artista.mis-perfiles')
            ->with('success', '¡Perfil completado! Será revisado por el equipo de Cultura.');
    }


    /* --------------------------------------------------------------------------
    |  DASHBOARD DEL ARTISTA
    * -------------------------------------------------------------------------- */


    /**
     * Muestra el panel principal del artista.
     *
     * Lista todos los perfiles artísticos pertenecientes al usuario
     * autenticado junto con los eventos asociados.
     *
     * @return \Illuminate\View\View
     */
    public function misPerfiles()
    {
        $artistas = Artista::where('user_id', Auth::id())
            ->with('disciplina')
            ->latest()
            ->get();

        $eventos = Evento::where('user_id', auth()->id())
            ->with('artistas')
            ->orderBy('fecha_inicio')
            ->get();

        return view('artista.mis-perfiles', compact('artistas', 'eventos'));
    }


    /**
     * Muestra el formulario de edición de un perfil artístico.
     *
     * Carga toda la información necesaria para modificar los datos
     * generales, géneros, redes sociales, contenido multimedia
     * y eventos asociados al perfil.
     *
     * Sólo el propietario del perfil puede acceder a esta sección.
     *
     * @param  Artista  $artista
     * @return \Illuminate\View\View
     */
    public function edit(Artista $artista)
    {
        // Solo el propietario del perfil puede editar
        abort_if($artista->user_id !== Auth::id(), 403);

        $disciplinas    = Disciplina::where('pendiente_revision', false)->orderBy('nombre')->get();
        $generos        = Genero::where('disciplina_id', $artista->disciplina_id)->orderBy('nombre')->get();
        $generosActivos = $artista->generos->pluck('id')->toArray();

        $fotos          = $artista->media()->where('tipo', 'foto')->orderBy('orden')->get();
        $videos         = $artista->media()->where('tipo', 'video_link')->orderBy('orden')->get();
        $tracks         = $artista->media()->where('tipo', 'audio_link')->orderBy('orden')->get();
        $redes          = $artista->redes->keyBy('plataforma');
        $redesConfig    = config('redes');
        $eventos          = $artista->eventos()->orderByDesc('fecha_inicio')->get();

        return view('artista.edit', compact(
            'artista', 'disciplinas', 'generos', 'generosActivos',
            'fotos', 'videos', 'tracks', 'redes', 'redesConfig', 'eventos'
        ));
    }

    /**
     * Actualiza la información general de un perfil artístico.
     *
     * También reemplaza la imagen de perfil si se envía una nueva
     * y sincroniza los géneros seleccionados.
     *
     * @param  UpdateArtistaRequest  $request
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateArtistaRequest $request, Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $data = $request->validated();

        if ($request->hasFile('img_perfil')) {
            //  Elimina la imagen anterior
            if ($artista->img_perfil) {
                Storage::disk('public')->delete($artista->img_perfil);
            }

            // Procesa la nueva imagen antes de almacenarla
            // para mantener un tamaño uniforme en el sitio.
            $file = $request->file('img_perfil');
            $filename = Str::random(20) . '.webp';

            $image = Image::read($file)
                ->scaleDown(width: 800) // nunca más ancho que 800px, mantiene proporción
                ->toWebp(quality: 75);  // convierte a webp, formato mucho más liviano

            Storage::disk('public')->put('artistas/' . $filename, (string) $image);
            $data['img_perfil'] = 'artistas/' . $filename;
        }

        $data['integrantes'] = array_values(
            array_filter($data['integrantes'] ?? [], fn($v) => trim($v) !== '')
        ) ?: null;

        $artista->update($data);
        $artista->generos()->sync($request->generos ?? []);

        return redirect()
            ->route('artista.edit', $artista->slug)
            ->with('success', 'Información actualizada.')
            ->with('tab', 'info');
        /* AGREGAR TAB ACTIVO PARA Q VUELVA AL TAB QUE ESTABA */
       /* return back()->with('success', '...')->with('tab', 'multimedia');*/
    }

    /* --------------------------------------------------------------------------
    |  GESTIÓN DE MULTIMEDIA
    * -------------------------------------------------------------------------- */

    /**
     * Agrega nuevas fotografías al perfil artístico.
     *
     * Procesa las imágenes recibidas, las optimiza en formato WebP
     * y las incorpora a la galería existente respetando el orden
     * de visualización.
     *
     * @param  Request  $request
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFotos(Request $request, Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $request->validate([
            'fotos'   => 'required|array|max:10',
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $orden = $artista->media()->where('tipo', 'foto')->max('orden') ?? 0;

        foreach ($request->file('fotos') as $foto) {

            $filename = Str::random(20) . '.webp';
            $image = Image::read($foto)
                ->scaleDown(width: 1200)
                ->toWebp(quality: 75);
            Storage::disk('public')->put('artistas/fotos/' . $filename, (string) $image);

            $artista->media()->create([
                'tipo'  => 'foto',
                'url'   => 'artistas/fotos/' . $filename,
                'orden' => ++$orden,
            ]);
        }

        return back()->with('success', 'Fotos agregadas correctamente.');
    }

    /**
     * Agrega contenido multimedia basado en enlaces externos.
     *
     * Permite incorporar nuevos videos de YouTube y audios
     * (por ejemplo Spotify) manteniendo el orden de visualización.
     *
     * Este método únicamente agrega nuevos registros.
     *
     * @param  Request  $request
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLinks(Request $request, Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $request->validate([
            'tracks'          => 'nullable|array',
            'tracks.*'        => 'nullable|url|max:255',
            'tracks_titulo.*' => 'nullable|string|max:255',
            'videos'          => 'nullable|array',
            'videos.*'        => 'nullable|url|max:255',
            'videos_titulo.*' => 'nullable|string|max:255',
        ]);

        if ($request->filled('tracks')) {
            $orden = $artista->media()->where('tipo', 'audio_link')->max('orden') ?? 0;
            foreach ($request->tracks as $i => $url) {
                if (empty($url)) continue;
                $artista->media()->create([
                    'tipo'   => 'audio_link',
                    'url'    => $url,
                    'titulo' => $request->tracks_titulo[$i] ?? null,
                    'orden'  => ++$orden,
                ]);
            }
        }

        if ($request->filled('videos')) {
            $orden = $artista->media()->where('tipo', 'video_link')->max('orden') ?? 0;
            foreach ($request->videos as $i => $url) {
                if (empty($url)) continue;
                $artista->media()->create([
                    'tipo'   => 'video_link',
                    'url'    => $url,
                    'titulo' => $request->videos_titulo[$i] ?? null,
                    'orden'  => ++$orden,
                ]);
            }
        }

        return back()->with('success', 'Links guardados correctamente.');
    }

    /**
     * Actualiza las redes sociales del perfil artístico.
     *
     * Crea, modifica o elimina las redes sociales enviadas desde
     * el formulario manteniendo sincronizada la información
     * almacenada en la base de datos.
     *
     * @param  Request  $request
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRedes(Request $request, Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $request->validate([
            'redes'   => 'nullable|array',
            'redes.*' => 'nullable|url|max:255',
        ]);

        foreach ($request->redes ?? [] as $plataforma => $url) {
            if (empty($url)) {
                // Si borra la URL, elimina el registro
                $artista->redes()->where('plataforma', $plataforma)->delete();
                continue;
            }
            $artista->redes()->updateOrCreate(
                ['plataforma' => $plataforma],
                ['url' => $url]
            );
        }

        return back()->with('success', 'Redes actualizadas.');
    }

    /**
     * Elimina un elemento multimedia del perfil artístico.
     *
     * Si el recurso corresponde a una fotografía, también elimina
     * el archivo físico almacenado en el servidor.
     *
     * @param  Artista  $artista
     * @param  Media    $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMedia(Artista $artista, Media $media)
    {
        abort_if($artista->user_id !== Auth::id(), 403);
        abort_if($media->artista_id !== $artista->id, 403);

        // Si el recurso corresponde a una imagen,
        // elimina también el archivo físico del disco.
        if ($media->tipo === 'foto') {
            Storage::disk('public')->delete($media->url);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Elimina una red social del perfil artístico.
     *
     * Sólo el propietario del perfil puede eliminar
     * las redes asociadas.
     *
     * @param  Artista        $artista
     * @param  ArtistaRedes   $red
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyRed(Artista $artista, ArtistaRedes $red)
    {
        abort_if($artista->user_id !== Auth::id(), 403);
        abort_if($red->artista_id !== $artista->id, 403);

        $red->delete();

        return response()->json(['success' => true]);
    }



    /**
     * Elimina un perfil artístico.
     *
     * Sólo el propietario del perfil puede realizar esta acción.
     *
     * Además elimina todo el contenido asociado al perfil, incluyendo:
     * - Imagen de perfil.
     * - Fotografías y archivos multimedia.
     * - Redes sociales.
     * - Relaciones con géneros.
     * - Relaciones con eventos.
     *
     * @param  Artista  $artista
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Artista $artista)
    {
        // Sólo el dueño puede eliminar el perfil
        abort_if($artista->user_id !== Auth::id(), 403);

        DB::transaction(function () use ($artista) {
            // Eliminar imagen de perfil
            if ($artista->img_perfil) {
                Storage::disk('public')->delete($artista->img_perfil);
            }

            // Eliminar fotos almacenadas
            foreach ($artista->media()->where('tipo', 'foto')->get() as $foto) {
                Storage::disk('public')->delete($foto->url);
            }

            // Eliminar multimedia
            $artista->media()->delete();
            // Eliminar redes sociales
            $artista->redes()->delete();
            // Eliminar relaciones con géneros
            $artista->generos()->detach();
            // Eliminar relaciones con eventos
            $artista->eventos()->detach();
            // Finalmente eliminar el perfil
            $artista->delete();
        });

        return redirect()
            ->route('artista.mis-perfiles')
            ->with('success', 'El perfil artístico fue eliminado correctamente.');
    }

    /* --------------------------------------------------------------------------
    |  BÚSQUEDAS Y FILTROS
    * -------------------------------------------------------------------------- */

    /**
     * Busca artistas aplicando filtros dinámicos.
     *
     * Permite filtrar el catálogo público por nombre artístico,
     * disciplina y género, devolviendo únicamente la información
     * necesaria para actualizar el listado mediante AJAX.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchArtists(Request $request){

        $query = Artista::where('visible', 1)->with(['disciplina', 'generos']);

        // Construye dinámicamente la consulta aplicando
        // únicamente los filtros enviados por el usuario.
        if ($request->filled('busqueda')) {
            $query->where('nombre_artistico', 'like', '%' . $request->busqueda . '%');
        }

        if ($request->filled('disciplina')) {
            $query->where('disciplina_id', $request->disciplina);
        }

        if ($request->filled('genero')) {
            $query->whereHas('generos', fn($q) => $q->where('generos.id', $request->genero));
        }

        // Devuelve únicamente los datos necesarios
        // para el listado público en formato JSON.
        return response()->json(
            $query->orderBy('nombre_artistico', 'desc')->get()->map(fn($a) => [
                'slug'             => $a->slug,
                'nombre_artistico' => $a->nombre_artistico,
                'localidad'        => $a->localidad,
                'disciplina'       => $a->disciplina?->nombre,
                'generos'          => $a->generos->pluck('nombre'),
                'img_perfil'       => $a->img_perfil ? asset('storage/' . $a->img_perfil) : asset('img/default.jpg'),
            ])
        );
    }
}



/*
TODO:
Guardar 2 versiones de la img, una para cards (pequeña) otra para portada (grande)
Pero tngo q agregar el cmapo en la db, migrar, etc...


if ($request->hasFile('img_perfil')) {
    $file = $request->file('img_perfil');
    $filename = Str::random(20);

    // Versión grande (perfil)
    $full = Image::read($file)->scaleDown(width: 800)->toWebp(quality: 75);
    Storage::disk('public')->put("artistas/{$filename}.webp", (string) $full);

    // Versión chica (card / listado)
    $thumb = Image::read($file)->scaleDown(width: 400)->toWebp(quality: 70);
    Storage::disk('public')->put("artistas/{$filename}-thumb.webp", (string) $thumb);

    $artista->img_perfil = "artistas/{$filename}.webp";
    $artista->img_perfil_thumb = "artistas/{$filename}-thumb.webp"; // nueva columna
}


*/
