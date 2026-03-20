<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controlador responsable del inicio y cierre de sesión y de redirigir por rol.
 *
 * Centraliza el flujo de autenticación del panel y evita que cada vista o ruta
 * tenga que decidir manualmente cuál es la pantalla inicial de cada usuario.
 */
class AuthController extends Controller
{
    /**
     * Muestra el login si no existe sesión o redirige al panel correcto si el usuario ya está autenticado.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole($request->user()->role);
        }

        return view('auth.login');
    }

    /**
     * Valida credenciales, exige que la cuenta esté activa y guarda el rol en sesión.
     */
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

        // Solo permite iniciar sesión a cuentas marcadas como activas en base de datos.
        $credentials['is_active'] = true;

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son correctas.',
            ]);
        }

        // Regenera la sesión para evitar fijación de sesión tras el login.
        $request->session()->regenerate();
        $request->session()->put('role', $request->user()->role);

        return $this->redirectByRole($request->user()->role);
    }

    /**
     * Cierra la sesión actual y limpia por completo los datos temporales del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Resuelve la página de aterrizaje según el rol funcional del usuario.
     */
    private function redirectByRole(string $role): RedirectResponse
    {
        // Envía a cada usuario a la zona principal de trabajo de su perfil.
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
