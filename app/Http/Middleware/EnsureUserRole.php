<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $currentRole = (string) $request->session()->get('role', 'invitado');

        if (empty($roles) || in_array($currentRole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a esta seccion.');
    }
}
