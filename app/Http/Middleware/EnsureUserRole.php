<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $currentRole = (string) $user->role;

        if (empty($roles) || in_array($currentRole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a esta seccion.');
    }
}
