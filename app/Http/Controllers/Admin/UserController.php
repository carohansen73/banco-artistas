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
}
