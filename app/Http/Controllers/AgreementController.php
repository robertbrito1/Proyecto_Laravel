<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador que gestiona el ciclo de vida completo de los convenios.
 *
 * Aquí se concentra el flujo del módulo: listado, alta, edición, firma y
 * visualización detallada del convenio con sus relaciones principales.
 */
class AgreementController extends Controller
{
    /** Estados permitidos del convenio y su etiqueta visible en pantalla. */
    public const STATUSES = [
        'borrador'            => 'Borrador',
        'pendiente_firma'     => 'Pendiente de firma',
        'firmado_empresa'     => 'Firmado por empresa',
        'firmado_centro'      => 'Firmado por centro',
        'activo'              => 'Activo',
        'finalizado'          => 'Finalizado',
        'cancelado'           => 'Cancelado',
        'renovacion'          => 'En renovación',
    ];

    /** Roles con permiso para crear o editar convenios. */
    private const WRITE_ALLOWED_ROLES = ['administrador', 'coordinadorFFE', 'secretaria', 'tutor', 'profesor'];

    /** Roles con permiso para marcar un convenio como firmado por el centro. */
    private const SIGN_ALLOWED_ROLES = ['direccion', 'administrador'];

    // ─── Listado ─────────────────────────────────────────────────────────────

    /**
     * Genera el listado paginado de convenios y aplica filtros por estado, departamento y empresa.
     */
    public function index(Request $request)
    {
        $agreementQuery = Agreement::with(['company', 'department', 'assignedTeacher'])
            ->latest();

        // Aplica los filtros opcionales enviados por la query string.
        if ($request->filled('status')) {
            $agreementQuery->where('status', $request->input('status'));
        }
        if ($request->filled('department_id')) {
            $agreementQuery->where('department_id', $request->input('department_id'));
        }
        // La búsqueda textual se resuelve sobre el nombre comercial de la empresa vinculada.
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $agreementQuery->whereHas('company', fn ($companyQuery) => $companyQuery->where('business_name', 'like', "%$searchTerm%"));
        }

        $agreements  = $agreementQuery->paginate(15)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $statuses    = self::STATUSES;

        return view('convenios.index', compact('agreements', 'departments', 'statuses'));
    }

    // ─── Crear ───────────────────────────────────────────────────────────────

    /**
     * Prepara catálogos y permisos para mostrar el formulario de alta de convenios.
     */
    public function create()
    {
        $this->authorizeWrite();

        $companies   = Company::orderBy('business_name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $teachers    = User::whereIn('role', ['tutor', 'profesor'])->orderBy('name')->get();
        $statuses    = self::STATUSES;

        return view('convenios.create', compact('companies', 'departments', 'teachers', 'statuses'));
    }

    /**
     * Valida los datos recibidos y crea un convenio asignando como autor al usuario autenticado.
     */
    public function store(Request $request)
    {
        $this->authorizeWrite();

        $data = $request->validate([
            'company_id'               => 'required|exists:companies,id',
            'department_id'            => 'required|exists:departments,id',
            'assigned_teacher_id'      => 'nullable|exists:users,id',
            'ies_tutor_user_id'        => 'nullable|exists:users,id',
            'management_contact_name'  => 'nullable|string|max:255',
            'management_contact_phone' => 'nullable|string|max:30',
            'management_contact_email' => 'nullable|email|max:255',
            'status'                   => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'notes'                    => 'nullable|string|max:2000',
        ]);

        // Guarda quién registró inicialmente el convenio para fines de trazabilidad.
        $data['created_by_user_id'] = Auth::id();

        $agreement = Agreement::create($data);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio creado correctamente.');
    }

    // ─── Ver detalle ─────────────────────────────────────────────────────────

    /**
     * Muestra el detalle del convenio con relaciones, permisos de edición y capacidad de firma.
     */
    public function show(Agreement $agreement)
    {
        $agreement->load([
            'company',
            'department',
            'assignedTeacher',
            'iesTutor',
            'companyTutors',
            'documents',
        ]);

        $statuses    = self::STATUSES;
        $canSign     = in_array(Auth::user()->role, self::SIGN_ALLOWED_ROLES, true);
        $canEdit     = in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true);

        return view('convenios.show', compact('agreement', 'statuses', 'canSign', 'canEdit'));
    }

    // ─── Editar ──────────────────────────────────────────────────────────────

    /**
     * Carga el formulario de edición reutilizando los mismos catálogos que en el alta.
     */
    public function edit(Agreement $agreement)
    {
        $this->authorizeWrite();

        $companies   = Company::orderBy('business_name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $teachers    = User::whereIn('role', ['tutor', 'profesor'])->orderBy('name')->get();
        $statuses    = self::STATUSES;

        return view('convenios.edit', compact('agreement', 'companies', 'departments', 'teachers', 'statuses'));
    }

    /**
     * Actualiza un convenio existente con las mismas reglas de validación del alta.
     */
    public function update(Request $request, Agreement $agreement)
    {
        $this->authorizeWrite();

        $data = $request->validate([
            'company_id'               => 'required|exists:companies,id',
            'department_id'            => 'required|exists:departments,id',
            'assigned_teacher_id'      => 'nullable|exists:users,id',
            'ies_tutor_user_id'        => 'nullable|exists:users,id',
            'management_contact_name'  => 'nullable|string|max:255',
            'management_contact_phone' => 'nullable|string|max:30',
            'management_contact_email' => 'nullable|email|max:255',
            'status'                   => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'notes'                    => 'nullable|string|max:2000',
        ]);

        $agreement->update($data);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio actualizado correctamente.');
    }

    // ─── Eliminar ────────────────────────────────────────────────────────────

    /**
     * Elimina un convenio. Se limita a perfiles con mayor responsabilidad funcional.
     */
    public function destroy(Agreement $agreement)
    {
        abort_unless(
            in_array(Auth::user()->role, ['administrador', 'coordinadorFFE']),
            403
        );

        $agreement->delete();

        return redirect()->route('convenios.index')
            ->with('success', 'Convenio eliminado.');
    }

    // ─── Firmar (Dirección) ──────────────────────────────────────────────────

    /**
     * Marca el convenio como firmado por el centro y guarda la fecha de firma interna.
     */
    public function sign(Agreement $agreement)
    {
        abort_unless(in_array(Auth::user()->role, self::SIGN_ALLOWED_ROLES, true), 403);

        $agreement->update([
            'status'    => 'firmado_centro',
            'signed_at' => now(),
        ]);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio marcado como firmado por el centro.');
    }

    // ─── Soporte interno ─────────────────────────────────────────────────────

    /**
     * Verifica si el usuario actual puede crear o modificar convenios.
     */
    private function authorizeWrite(): void
    {
        // Restringe las operaciones de escritura a los roles autorizados.
        abort_unless(in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true), 403);
    }
}
