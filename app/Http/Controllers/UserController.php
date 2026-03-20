<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Controlador que gestiona el alta, consulta, edición y baja de usuarios del panel.
 *
 * Solo accesible para el rol administrador. Permite asignar roles, departamentos
 * y activar o desactivar cuentas sin eliminar datos históricos.
 */
class UserController extends Controller
{
    /** Roles disponibles en el sistema y su etiqueta visible. */
    public const ROLES = [
        'administrador'  => 'Administrador',
        'direccion'      => 'Dirección',
        'coordinadorFFE' => 'Coordinador FFE',
        'tutor'          => 'Tutor',
        'profesor'       => 'Profesor',
        'secretaria'     => 'Secretaría',
        'empresa'        => 'Empresa',
    ];

    // ─── Listado ─────────────────────────────────────────────────────────────

    /**
     * Muestra el listado paginado de usuarios con filtros por búsqueda, rol y estado.
     */
    public function index(Request $request)
    {
        $userQuery = User::with('department')->latest();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $userQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%$searchTerm%")
                  ->orWhere('email', 'like', "%$searchTerm%")
                  ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }

        if ($request->filled('role')) {
            $userQuery->where('role', $request->input('role'));
        }

        if ($request->filled('is_active')) {
            $userQuery->where('is_active', $request->input('is_active') === '1');
        }

        $users       = $userQuery->paginate(20)->withQueryString();
        $roles       = self::ROLES;
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.usuarios.index', compact('users', 'roles', 'departments'));
    }

    // ─── Crear ───────────────────────────────────────────────────────────────

    /**
     * Muestra el formulario de alta de usuario con los catálogos necesarios.
     */
    public function create()
    {
        $roles       = self::ROLES;
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.usuarios.create', compact('roles', 'departments'));
    }

    /**
     * Valida los datos recibidos y registra un nuevo usuario en el sistema.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|in:' . implode(',', array_keys(self::ROLES)),
            'department_id' => 'nullable|exists:departments,id',
            'phone'         => 'nullable|string|max:30',
            'is_active'     => 'boolean',
        ]);

        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);

        return redirect()->route('admin.usuarios.show', $user)
            ->with('success', 'Usuario creado correctamente.');
    }

    // ─── Ver detalle ─────────────────────────────────────────────────────────

    /**
     * Muestra la ficha completa de un usuario con su departamento y actividad asociada.
     */
    public function show(User $user)
    {
        $user->load('department');
        $roles = self::ROLES;

        return view('admin.usuarios.show', compact('user', 'roles'));
    }

    // ─── Editar ──────────────────────────────────────────────────────────────

    /**
     * Carga el formulario de edición con los datos actuales del usuario.
     */
    public function edit(User $user)
    {
        $roles       = self::ROLES;
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.usuarios.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Actualiza los datos de un usuario. La contraseña solo se modifica si se envía.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|in:' . implode(',', array_keys(self::ROLES)),
            'department_id' => 'nullable|exists:departments,id',
            'phone'         => 'nullable|string|max:30',
            'is_active'     => 'boolean',
        ]);

        // Solo actualiza la contraseña si se proporcionó una nueva.
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('admin.usuarios.show', $user)
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // ─── Activar / Desactivar ────────────────────────────────────────────────

    /**
     * Alterna el estado activo/inactivo del usuario sin eliminarlo.
     * No permite desactivar la propia cuenta del administrador.
     */
    public function toggleActive(User $user)
    {
        // Impide que el administrador se desactive a sí mismo.
        abort_if($user->id === Auth::id() && $user->is_active, 403, 'No puedes desactivar tu propia cuenta.');

        $user->update(['is_active' => !$user->is_active]);

        $label = $user->is_active ? 'activado' : 'desactivado';

        return redirect()->route('admin.usuarios.show', $user)
            ->with('success', "Usuario $label correctamente.");
    }

    // ─── Eliminar ────────────────────────────────────────────────────────────

    /**
     * Elimina un usuario de forma definitiva. No permite eliminar la propia cuenta.
     */
    public function destroy(User $user)
    {
        abort_if($user->id === Auth::id(), 403, 'No puedes eliminar tu propia cuenta.');

        $user->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado.');
    }
}
