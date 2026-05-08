<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyOutreachLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * Controla el registro y búsqueda de empresas contactadas para evitar duplicados.
 */
class CompanyOutreachLogController extends Controller
{
    public const STATUSES = [
        'contactada' => 'Contactada',
        'pendiente_respuesta' => 'Pendiente respuesta',
        'descartada' => 'Descartada',
    ];

    public function index(Request $request)
    {
        $logsQuery = CompanyOutreachLog::with(['teacher', 'company'])->latest('contacted_at');

        if ($request->filled('teacher_user_id')) {
            $logsQuery->where('teacher_user_id', $request->integer('teacher_user_id'));
        }

        if ($request->filled('status')) {
            $logsQuery->where('status', $request->input('status'));
        }

        $logsCollection = $logsQuery->get();

        if ($request->filled('search')) {
            $search = mb_strtolower(trim($request->input('search')));

            $logsCollection = $logsCollection->filter(function (CompanyOutreachLog $log) use ($search) {
                $haystack = mb_strtolower(implode(' ', array_filter([
                    $log->company_name,
                    $log->contact_email,
                    $log->contact_phone,
                    $log->company?->business_name,
                    $log->company?->email,
                    $log->company?->main_phone,
                    $log->teacher?->name,
                ])));

                return str_contains($haystack, $search);
            })->values();
        }

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pageItems = $logsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $logs = new LengthAwarePaginator($pageItems, $logsCollection->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        $teachers = User::whereIn('role', ['administrador', 'coordinadorFFE', 'tutor', 'profesor'])
            ->orderBy('name')
            ->get();
        $companies = Company::orderBy('business_name')->get();
        $statuses = self::STATUSES;

        return view('coordinacion.empresas-contactadas', compact('logs', 'teachers', 'companies', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'company_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'status' => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'notes' => 'nullable|string|max:2000',
            'contacted_at' => 'nullable|date',
        ]);

        if (blank($data['company_name'] ?? null) && ! empty($data['company_id'])) {
            $data['company_name'] = Company::find($data['company_id'])?->business_name;
        }

        if (blank($data['company_name'] ?? null)) {
            return back()->withErrors(['company_name' => 'Indica la empresa o selecciona una existente.'])->withInput();
        }

        $data['teacher_user_id'] = Auth::id();
        $data['contacted_at'] = $data['contacted_at'] ?? now();

        CompanyOutreachLog::create($data);

        return redirect()->route('coordinacion.empresas-contactadas')
            ->with('success', 'Contacto registrado correctamente.');
    }
}
