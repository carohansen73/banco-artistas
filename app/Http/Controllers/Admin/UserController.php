<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Disciplina;
use App\Models\Genero;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(): View
    {
       $users = User::with('roles')
        ->orderBy('name')
        ->paginate(20);

        return view('admin.usuarios.index', compact('users'));
    }

    /**
     * HABILIA / DESHABILITA UN USUARIO
     */
    public function toggleActive(Request $request, User $user)
    {
        // Evitar que un admin se bloquee a sí mismo
        if ($user->id === $request->user()->id) {
            return response()->json(['error' => 'No podés desactivarte a vos mismo.'], 403);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return response()->json([
            'is_active' => $user->is_active,
        ]);
    }


    public function updateRole(Request $request, User $user)
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'No podés cambiar tu propio rol.'], 422);
        }

        $request->validate([
            'role' => 'required|in:admin,artista,super-admin',
        ]);

        // Si es artista y tiene perfiles cargados, no permitir sacarle el rol
        if ($user->hasRole('artista') && $request->role !== 'artista' && $user->artistas()->exists()) {
            return response()->json([
                'error' => 'Este usuario tiene perfiles de artista activos. Pedile que los elimine antes de cambiarle el rol, o hacelo vos desde su perfil.',
            ], 422);
        }

        // Sync cambia el rol, assign va acumulando
        $user->syncRoles([$request->role]);

        return response()->json([
            'role'  => $request->role,
            'label' => ucfirst(str_replace('-', ' ', $request->role)),
        ]);
    }
}
