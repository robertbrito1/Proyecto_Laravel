<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que valida si el usuario autenticado pertenece a uno de los roles permitidos.
 */
class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            // Si no hay sesión activa, obliga a pasar por el formulario de acceso.
            return redirect()->route('login');
        }

        $currentRole = (string) $user->role;

        if (empty($roles) || in_array($currentRole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a esta seccion.');
    }
}
