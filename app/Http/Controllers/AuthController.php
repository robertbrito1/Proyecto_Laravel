<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole($request->user()->role);
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Introduce tu correo.',
            'email.email' => 'El correo no tiene un formato valido.',
            'password.required' => 'Introduce tu password.',
        ]);

        $credentials['is_active'] = true;

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son correctas.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('role', $request->user()->role);

        return $this->redirectByRole($request->user()->role);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'administrador' => redirect()->route('admin.dashboard'),
            'direccion' => redirect()->route('direccion.convenios'),
            'coordinadorFFE' => redirect()->route('coordinacion.departamentos'),
            'tutor', 'profesor' => redirect()->route('coordinacion.empresas-contactadas'),
            'secretaria', 'empresa' => redirect()->route('panel.home'),
            default => redirect()->route('panel.home'),
        };
    }
}
