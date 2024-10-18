<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();

            // Redirigir si el rol no coincide
            if (($role == 'comprador' && $user->role_id != 1) || ($role == 'vendedor' && $user->role_id != 2)) {
                return redirect('/unauthorized'); // Ruta a la que redirigir si no coincide
            }

            return $next($request);
        }

        return redirect('/login'); // Redirigir si no está autenticado
    }
}
