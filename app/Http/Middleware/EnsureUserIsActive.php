<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Cierra la sesión de inmediato si al usuario le deshabilitaron
     * la cuenta mientras estaba logueado (is_active sólo se chequea
     * en el login, no en cada request, así que sin esto seguiría
     * navegando con acceso completo hasta cerrar sesión por su cuenta).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta fue deshabilitada. Contactá al administrador.',
            ]);
        }

        return $next($request);
    }
}
