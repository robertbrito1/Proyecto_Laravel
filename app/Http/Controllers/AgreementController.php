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
    /** Permitted statuses and their display labels */
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

    /** Roles that can create/edit agreements */
    private const CAN_WRITE = ['administrador', 'coordinadorFFE', 'secretaria', 'tutor', 'profesor'];

    /** Roles that can sign (change to firmado_centro) */
    private const CAN_SIGN = ['direccion', 'administrador'];

    // ─── List ────────────────────────────────────────────────────────────────

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

    // ─── Create ──────────────────────────────────────────────────────────────

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

    // ─── Show ────────────────────────────────────────────────────────────────

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
        $canEdit     = in_array(Auth::user()->role, self::CAN_WRITE);

        return view('convenios.show', compact('agreement', 'statuses', 'canSign', 'canEdit'));
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

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

    // ─── Delete ──────────────────────────────────────────────────────────────

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

    // ─── Sign (Dirección) ────────────────────────────────────────────────────

    public function sign(Agreement $agreement)
    {
        abort_unless(in_array(Auth::user()->role, self::CAN_SIGN), 403);

        $agreement->update([
            'status'    => 'firmado_centro',
            'signed_at' => now(),
        ]);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio marcado como firmado por el centro.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeWrite(): void
    {
        abort_unless(in_array(Auth::user()->role, self::CAN_WRITE), 403);
    }
}
