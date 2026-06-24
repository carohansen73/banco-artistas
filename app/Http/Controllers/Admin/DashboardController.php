<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Evento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hoy = Carbon::today();

        // Status principales
        // 1. Artista
        $totalArtistas = Artista::count();
        $pendientesCount = Artista::where('visible', false)->count();
        $nuevosEsteMes = Artista::whereMonth('created_at', $hoy->month)
            ->whereYear('created_at', $hoy->year)
            ->count();

        // --- Artistas pendientes (máx. 5 para la tabla del dashboard) ---
        $artistas_pendientes = Artista::with(['user', 'disciplina'])
            ->where('visible', false)
            ->latest()
            ->take(5)
            ->get();

        // --- Artistas agrupados por disciplina (ordenados de mayor a menor) ---
        $artistas_por_disciplina = DB::table('artistas')
            ->join('disciplinas', 'artistas.disciplina_id', '=', 'disciplinas.id')
            ->select('disciplinas.nombre', DB::raw('count(*) as total'))
            ->groupBy('disciplinas.id', 'disciplinas.nombre')
            ->orderByDesc('total')
            ->get();

        // 2. Usuarios
        $totalUsuarios = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->count();
        $usuariosSinPerfil = User::doesntHave('artistas')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->count();

        // 3. Eventos
        $eventosVigentes   = Evento::where('fecha_fin', '>=', $hoy)->count();
        $eventosEstaSemana = Evento::where('fecha_inicio', '>=', $hoy)
                                   ->where('fecha_inicio', '<=', $hoy->copy()->endOfWeek())
                                   ->count();
        // --- Próximos 4 eventos ---
        $proximos_eventos = Evento::where('fecha_inicio', '>=', $hoy)
            ->orderBy('fecha_inicio')
            ->take(4)
            ->get();


        return view('admin.dashboard',  compact(
            'totalArtistas',
            'pendientesCount',
            'nuevosEsteMes',
            'totalUsuarios',
            'usuariosSinPerfil',
            'eventosVigentes',
            'eventosEstaSemana',
            'artistas_pendientes',
            'proximos_eventos',
            'artistas_por_disciplina',
        ));
    }
}
