<?php

namespace App\Http\Controllers;

use App\Models\Artista;
use App\Http\Requests\StoreArtistaRequest;
use App\Http\Requests\UpdateArtistaRequest;
use App\Models\Disciplina;
use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtistaController extends Controller
{

    public function home()
    {
        $artistas = Artista::where('visible', 1)->get();
        return view('public.home', compact('artistas'));
    }

    /**
     * Listado de artistas
     * Vista publica!
     */
    public function index()
    {
        $artistas = Artista::where('visible', 1)
            ->with('disciplina', 'generos')
            ->get();
        $disciplinas = Disciplina::orderBy('nombre')->get();
        $generos     = Genero::orderBy('nombre')->get();

        return view('public.index', compact('artistas', 'disciplinas', 'generos'));
    }

    /**
     * Formulario para crear nuevo perfil de artista.
     */
    public function create()
    {
        $user = Auth::user();
        $disciplinas = Disciplina::where('pendiente_revision', false)->orderBy('nombre')->get();
        return view('artista.create', compact('disciplinas'));
    }

    /**
     * Guardar nuevo perfil de artista.
     */
    public function store(StoreArtistaRequest $request)
    {
        $data = $request->validated();

        // Chequear que un usuario no inscriba 2 veces su proyecto artistico.
        $existe = Artista::where('user_id', Auth::id())
            ->whereRaw('LOWER(nombre_artistico) = ?', [strtolower($data['nombre_artistico'])])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['nombre_artistico' => 'Ya tenés una banda registrada con ese nombre.']);
        }

        // Imagen de perfil
        if ($request->hasFile('img_perfil')) {
            $data['img_perfil'] = $request->file('img_perfil')->store('artistas/perfiles', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['slug']    = Str::slug($data['nombre_artistico']) . '-' . uniqid();
        $data['visible'] = false;

        $artista = Artista::create($data);

        // Géneros (pivot)
        if ($request->filled('generos')) {
            $artista->generos()->sync($request->generos);
        }

        // Redirigir al apso 2
        return redirect()
            ->route('artista.create.paso2', $artista->slug)
            ->with('success', '¡Perfil creado! Ahora completá tus redes y contenido.');
        // return redirect()->route('dashboard')->with('success', '¡Perfil artístico creado! Pronto será revisado por el equipo.');
    }


    // Mostrar paso 2
    public function createPaso2(Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $redes = $artista->redes->keyBy('plataforma');
        $redesConfig = config('redes');

        return view('artista.create-paso2', compact('artista', 'redes', 'redesConfig'));
    }

    // Guardar paso 2
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

        // Redes sociales
        if ($request->filled('redes')) {
            foreach ($request->redes as $plataforma => $url) {
                if (empty($url)) continue;

                $artista->redes()->updateOrCreate(
                    ['plataforma' => $plataforma],
                    ['url' => $url]
                );
            }
        }

        // Fotos
        if ($request->hasFile('fotos')) {
            $orden = $artista->fotos()->max('orden') ?? 0;
            $fotos = $request->file('fotos');

            foreach ((array) $fotos as $foto) {
                if (! $foto) {
                    continue;
                }
                $path = $foto->store('artistas/fotos', 'public');
                $artista->media()->create([
                    'tipo'  => 'foto',
                    'url'   => $path,
                    'orden' => ++$orden,
                ]);
            }
        }

        // Tracks de Spotify
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

        // Videos de YouTube
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

        return redirect()
            ->route('dashboard')
            ->with('success', '¡Perfil completado! Será revisado por el equipo de Cultura.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Artista $artista)
    {
        $artista->load(['disciplina', 'generos', 'redes', 'media']);

        $fotos      = $artista->media->where('tipo', 'foto');
        $videos     = $artista->media->where('tipo', 'video_link');
        $audios     = $artista->media->where('tipo', 'audio_link');


        return view('public.show', compact('artista', 'fotos', 'videos', 'audios'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Artista $artista)
    {
        // Solo el dueño puede editar
        abort_if($artista->user_id !== Auth::id(), 403);

        $disciplinas    = Disciplina::where('pendiente_revision', false)->orderBy('nombre')->get();
        $generos        = Genero::where('disciplina_id', $artista->disciplina_id)->orderBy('nombre')->get();
        $generosActivos = $artista->generos->pluck('id')->toArray();

        return view('artista.edit', compact('artista', 'disciplinas', 'generos', 'generosActivos'));
    }

    /**
     * Actualizar perfil.
     */
    public function update(UpdateArtistaRequest $request, Artista $artista)
    {
        abort_if($artista->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'nombre_artistico'      => 'required|string|max:255',
            'localidad'             => 'required|string|max:255',
            'telefono'              => 'required|string|max:50',
            'disciplina_id'         => 'required|exists:disciplinas,id',
            'generos'               => 'nullable|array',
            'generos.*'             => 'exists:generos,id',
            'descripcion_actividad' => 'required|string',
            'integrantes'           => 'nullable|integer|min:2',
            'tiene_formacion'       => 'required|boolean',
            'detalle_formacion'     => 'nullable|string',
            'anio_inicio'           => 'required|integer|min:1900|max:' . date('Y'),
            'tiene_documentacion'   => 'required|boolean',
            'acepta_difusion'       => 'required|boolean',
            'img_perfil'            => 'nullable|image|max:2048',
            'domicilio'             => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('img_perfil')) {
            // Borra la anterior si existe
            if ($artista->img_perfil) {
                Storage::disk('public')->delete($artista->img_perfil);
            }
            $data['img_perfil'] = $request->file('img_perfil')->store('artistas/perfiles', 'public');
        }

        $artista->update($data);
        $artista->generos()->sync($request->generos ?? []);

        return redirect()->route('dashboard')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artista $artista)
    {
        //
    }


    public function searchArtists(Request $request){

        $query = Artista::where('visible', 1)->with(['disciplina', 'generos']);

        if ($request->filled('busqueda')) {
            $query->where('nombre_artistico', 'like', '%' . $request->busqueda . '%');
        }

        if ($request->filled('disciplina')) {
            $query->where('disciplina_id', $request->disciplina);
        }

        if ($request->filled('genero')) {
            $query->whereHas('generos', fn($q) => $q->where('generos.id', $request->genero));
        }

        return response()->json(
            $query->get()->map(fn($a) => [
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
