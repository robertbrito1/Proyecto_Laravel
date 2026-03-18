<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgreementController extends Controller
{
    /** Estados permitidos y sus etiquetas (deben coincidir con el enum de la migración de convenios) */
    public const STATUSES = [
        'borrador'                => 'Borrador',
        'pendiente_generacion'    => 'Pendiente de generación',
        'generado'                => 'Generado',
        'pendiente_firma_empresa' => 'Pendiente firma empresa',
        'firmado_empresa'         => 'Firmado por empresa',
        'pendiente_firma_centro'  => 'Pendiente firma centro',
        'en_vigor'                => 'En vigor',
        'erroneo'                 => 'Erróneo',
        'caducado'                => 'Caducado',
    ];

    /** Roles que pueden crear/editar convenios */
    private const CAN_WRITE = ['administrador', 'coordinadorFFE', 'secretaria', 'tutor', 'profesor'];

    /** Roles que pueden firmar (cambiar a en_vigor) */
    private const CAN_SIGN = ['direccion', 'administrador'];

    // ─── Listado ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Agreement::with(['company', 'department', 'assignedTeacher'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('company', fn ($q) => $q->where('business_name', 'like', "%$search%"));
        }

        $agreements  = $query->paginate(15)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $statuses    = self::STATUSES;

        return view('convenios.index', compact('agreements', 'departments', 'statuses'));
    }

    // ─── Crear ───────────────────────────────────────────────────────────────

    public function create()
    {
        $this->authorizeWrite();

        $companies   = Company::orderBy('business_name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $teachers    = User::whereIn('role', ['tutor', 'profesor'])->orderBy('name')->get();
        $statuses    = self::STATUSES;

        return view('convenios.create', compact('companies', 'departments', 'teachers', 'statuses'));
    }

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

        $data['created_by_user_id'] = Auth::id();

        $agreement = Agreement::create($data);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio creado correctamente.');
    }

    // ─── Detalle ─────────────────────────────────────────────────────────────

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
        $canSign     = in_array(Auth::user()->role, self::CAN_SIGN);
        $canEdit     = in_array(Auth::user()->role, self::CAN_WRITE) &&
                       ! in_array($agreement->status, ['en_vigor', 'caducado']);

        return view('convenios.show', compact('agreement', 'statuses', 'canSign', 'canEdit'));
    }

    // ─── Editar ──────────────────────────────────────────────────────────────

    public function edit(Agreement $agreement)
    {
        $this->authorizeWrite();

        $companies   = Company::orderBy('business_name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $teachers    = User::whereIn('role', ['tutor', 'profesor'])->orderBy('name')->get();
        $statuses    = self::STATUSES;

        return view('convenios.edit', compact('agreement', 'companies', 'departments', 'teachers', 'statuses'));
    }

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

    public function sign(Agreement $agreement)
    {
        abort_unless(in_array(Auth::user()->role, self::CAN_SIGN), 403);

        $agreement->update([
            'status'    => 'en_vigor',
            'signed_at' => now(),
        ]);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio marcado como firmado por el centro.');
    }

    // ─── Utilidades ──────────────────────────────────────────────────────────

    private function authorizeWrite(): void
    {
        abort_unless(in_array(Auth::user()->role, self::CAN_WRITE), 403);
    }
}
