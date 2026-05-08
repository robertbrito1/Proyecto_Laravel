<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\AgreementDocument;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controlador que gestiona el ciclo de vida completo de los convenios.
 *
 * Añade filtros por vigencia, carga masiva desde CSV compatible con Excel,
 * exportación de informes y versionado de PDF en cada estado del convenio.
 */
class AgreementController extends Controller
{
    /** Estados permitidos del convenio y su etiqueta visible en pantalla. */
    public const STATUSES = [
        'borrador' => 'Borrador',
        'pendiente_generacion' => 'Pendiente de generación',
        'generado' => 'Generado por secretaría',
        'pendiente_firma_empresa' => 'Pendiente firma empresa',
        'firmado_empresa' => 'Firmado por empresa',
        'pendiente_firma_centro' => 'Pendiente firma centro',
        'en_vigor' => 'En vigor',
        'erroneo' => 'Erróneo',
        'caducado' => 'Caducado',
    ];

    /** Estados de los documentos versionados asociados al convenio. */
    public const DOCUMENT_STATUSES = [
        'generado' => 'Generado',
        'firmado_empresa' => 'Firmado por empresa',
        'firmado_centro' => 'Firmado por centro',
        'erroneo' => 'Erróneo',
    ];

    /** Roles con permiso para crear o editar convenios. */
    private const WRITE_ALLOWED_ROLES = ['administrador', 'coordinadorFFE', 'secretaria', 'tutor', 'profesor'];

    /** Roles con permiso para marcar un convenio como firmado por el centro. */
    private const SIGN_ALLOWED_ROLES = ['direccion', 'administrador'];

    /** Roles con permiso para subir o corregir documentos. */
    private const DOCUMENT_ALLOWED_ROLES = ['administrador', 'direccion', 'coordinadorFFE', 'secretaria', 'tutor', 'empresa'];

    public function index(Request $request)
    {
        $agreementQuery = $this->buildFilteredQuery($request);

        $agreements = $agreementQuery->paginate(15)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $statuses = self::STATUSES;
        $validityOptions = [
            'vigentes' => 'En vigor',
            'renovar_1y' => 'Renovar en menos de 1 año',
            'renovar_6m' => 'Renovar en menos de 6 meses',
            'renovar_3m' => 'Renovar en menos de 3 meses',
            'caducados' => 'Caducados',
        ];
        $summary = [
            'vigentes' => Agreement::vigentes()->count(),
            'renovar_1y' => Agreement::proximosACaducar(12)->count(),
            'renovar_6m' => Agreement::proximosACaducar(6)->count(),
            'renovar_3m' => Agreement::proximosACaducar(3)->count(),
            'caducados' => Agreement::caducados()->count(),
        ];

        return view('convenios.index', compact('agreements', 'departments', 'statuses', 'validityOptions', 'summary'));
    }

    public function create()
    {
        $this->authorizeWrite();

        $companies = Company::orderBy('business_name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $teachers = User::whereIn('role', ['tutor', 'profesor'])->orderBy('name')->get();
        $statuses = self::STATUSES;

        return view('convenios.create', compact('companies', 'departments', 'teachers', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeWrite();

        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'assigned_teacher_id' => 'nullable|exists:users,id',
            'ies_tutor_user_id' => 'nullable|exists:users,id',
            'management_contact_name' => 'nullable|string|max:255',
            'management_contact_phone' => 'nullable|string|max:30',
            'management_contact_email' => 'nullable|email|max:255',
            'status' => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'notes' => 'nullable|string|max:2000',
        ]);

        $data['created_by_user_id'] = Auth::id();

        $agreement = Agreement::create($data);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio creado correctamente.');
    }

    public function show(Agreement $agreement)
    {
        $agreement->load([
            'company',
            'department',
            'assignedTeacher',
            'iesTutor',
            'companyTutors',
            'documents.uploadedBy',
        ]);

        $agreement->setRelation(
            'documents',
            $agreement->documents->sortByDesc('version')->values()
        );

        $statuses = self::STATUSES;
        $documentStatuses = self::DOCUMENT_STATUSES;
        $canSign = in_array(Auth::user()->role, self::SIGN_ALLOWED_ROLES, true);
        $canEdit = in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true);
        $canManageDocuments = in_array(Auth::user()->role, self::DOCUMENT_ALLOWED_ROLES, true);

        return view('convenios.show', compact('agreement', 'statuses', 'documentStatuses', 'canSign', 'canEdit', 'canManageDocuments'));
    }

    public function edit(Agreement $agreement)
    {
        $this->authorizeWrite();

        $companies = Company::orderBy('business_name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $teachers = User::whereIn('role', ['tutor', 'profesor'])->orderBy('name')->get();
        $statuses = self::STATUSES;

        return view('convenios.edit', compact('agreement', 'companies', 'departments', 'teachers', 'statuses'));
    }

    public function update(Request $request, Agreement $agreement): RedirectResponse
    {
        $this->authorizeWrite();

        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'assigned_teacher_id' => 'nullable|exists:users,id',
            'ies_tutor_user_id' => 'nullable|exists:users,id',
            'management_contact_name' => 'nullable|string|max:255',
            'management_contact_phone' => 'nullable|string|max:30',
            'management_contact_email' => 'nullable|email|max:255',
            'status' => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'notes' => 'nullable|string|max:2000',
        ]);

        $agreement->update($data);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio actualizado correctamente.');
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $this->authorizeWrite();

        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'import_file.required' => 'Selecciona un archivo CSV exportado desde Excel.',
            'import_file.mimes' => 'El archivo debe estar en formato CSV.',
        ]);

        $rows = array_map('str_getcsv', file($request->file('import_file')->getRealPath()));

        if (count($rows) < 2) {
            return back()->withErrors(['import_file' => 'El archivo no contiene filas válidas para importar.']);
        }

        $header = array_map(function ($value) {
            return Str::of((string) $value)->trim()->lower()->replace([' ', '-'], '_')->toString();
        }, array_shift($rows));

        $imported = 0;

        foreach ($rows as $row) {
            if (! array_filter($row, fn ($value) => trim((string) $value) !== '')) {
                continue;
            }

            $row = array_pad($row, count($header), null);
            $data = array_combine($header, $row);
            $companyName = trim((string) ($data['empresa'] ?? $data['business_name'] ?? ''));

            if ($companyName === '') {
                continue;
            }

            $company = Company::firstOrCreate(
                ['business_name' => $companyName],
                [
                    'tax_id' => $this->nullIfBlank($data['cif'] ?? $data['tax_id'] ?? null),
                    'email' => $this->nullIfBlank($data['email_empresa'] ?? $data['email'] ?? null),
                    'main_phone' => $this->nullIfBlank($data['telefono_empresa'] ?? $data['telefono'] ?? null),
                    'activity' => $this->nullIfBlank($data['actividad'] ?? $data['activity'] ?? null),
                    'category' => $this->normalizeCategory($data['categoria'] ?? 'funciona'),
                ]
            );

            $department = Department::query()
                ->where('code', $this->nullIfBlank($data['departamento_codigo'] ?? $data['department_code'] ?? null))
                ->orWhere('name', $this->nullIfBlank($data['departamento'] ?? $data['department'] ?? null))
                ->first();

            $assignedTeacher = User::where('email', $this->nullIfBlank($data['profesor_email'] ?? $data['assigned_teacher_email'] ?? null))->first();
            $iesTutor = User::where('email', $this->nullIfBlank($data['tutor_ies_email'] ?? $data['ies_tutor_email'] ?? null))->first();

            Agreement::create([
                'company_id' => $company->id,
                'department_id' => $department?->id,
                'assigned_teacher_id' => $assignedTeacher?->id,
                'ies_tutor_user_id' => $iesTutor?->id,
                'created_by_user_id' => Auth::id(),
                'management_contact_name' => $this->nullIfBlank($data['responsable_nombre'] ?? $data['management_contact_name'] ?? null),
                'management_contact_phone' => $this->nullIfBlank($data['responsable_telefono'] ?? $data['management_contact_phone'] ?? null),
                'management_contact_email' => $this->nullIfBlank($data['responsable_email'] ?? $data['management_contact_email'] ?? null),
                'status' => $this->normalizeStatus($data['estado'] ?? $data['status'] ?? 'borrador'),
                'signed_at' => $this->nullIfBlank($data['fecha_firma'] ?? $data['signed_at'] ?? null),
                'notes' => $this->nullIfBlank($data['observaciones'] ?? $data['notes'] ?? null),
            ]);

            $imported++;
        }

        return redirect()->route('convenios.index')
            ->with('success', "Se importaron {$imported} convenios desde el archivo Excel/CSV.");
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $agreements = $this->buildFilteredQuery($request)->get();

        return response()->streamDownload(function () use ($agreements) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['Empresa', 'Categoria', 'Departamento', 'Tutor centro', 'Estado', 'Vigencia', 'Fecha firma', 'Caduca el', 'Observaciones'], ';');

            foreach ($agreements as $agreement) {
                fputcsv($handle, [
                    $agreement->company?->business_name,
                    $agreement->company?->category,
                    $agreement->department?->name,
                    $agreement->assignedTeacher?->name,
                    self::STATUSES[$agreement->status] ?? $agreement->status,
                    $agreement->validity_label,
                    $agreement->signed_at?->format('d/m/Y'),
                    $agreement->expires_at ? date('d/m/Y', strtotime($agreement->expires_at)) : '',
                    $agreement->notes,
                ], ';');
            }

            fclose($handle);
        }, 'informe_convenios.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function uploadDocument(Request $request, Agreement $agreement): RedirectResponse
    {
        abort_unless(in_array(Auth::user()->role, self::DOCUMENT_ALLOWED_ROLES, true), 403);

        $data = $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240',
            'document_status' => 'required|in:' . implode(',', array_keys(self::DOCUMENT_STATUSES)),
            'error_reason' => 'nullable|required_if:document_status,erroneo|string|max:2000',
        ]);

        $version = ((int) $agreement->documents()->max('version')) + 1;
        $path = $request->file('document')->store("agreement_documents/{$agreement->id}", 'local');

        AgreementDocument::create([
            'agreement_id' => $agreement->id,
            'uploaded_by_user_id' => Auth::id(),
            'status' => $data['document_status'],
            'version' => $version,
            'file_path' => $path,
            'error_reason' => $this->nullIfBlank($data['error_reason'] ?? null),
            'uploaded_at' => now(),
        ]);

        $agreement->update(match ($data['document_status']) {
            'generado' => ['status' => 'pendiente_firma_empresa'],
            'firmado_empresa' => ['status' => 'pendiente_firma_centro'],
            'firmado_centro' => ['status' => 'en_vigor', 'signed_at' => $agreement->signed_at ?? now()],
            'erroneo' => ['status' => 'erroneo'],
        });

        return redirect()->route('convenios.show', $agreement)
            ->with('success', "Documento PDF guardado como versión {$version}.");
    }

    public function downloadDocument(Agreement $agreement, AgreementDocument $document)
    {
        abort_unless($document->agreement_id === $agreement->id, 404);

        return response()->download(
            storage_path('app/' . $document->file_path),
            "convenio-{$agreement->id}-v{$document->version}.pdf"
        );
    }

    public function destroy(Agreement $agreement): RedirectResponse
    {
        abort_unless(
            in_array(Auth::user()->role, ['administrador', 'coordinadorFFE'], true),
            403
        );

        $agreement->delete();

        return redirect()->route('convenios.index')
            ->with('success', 'Convenio eliminado.');
    }

    public function sign(Agreement $agreement): RedirectResponse
    {
        abort_unless(in_array(Auth::user()->role, self::SIGN_ALLOWED_ROLES, true), 403);

        $agreement->update([
            'status' => 'en_vigor',
            'signed_at' => now(),
        ]);

        return redirect()->route('convenios.show', $agreement)
            ->with('success', 'Convenio marcado como firmado por el centro y en vigor.');
    }

    private function authorizeWrite(): void
    {
        abort_unless(in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true), 403);
    }

    private function buildFilteredQuery(Request $request)
    {
        $agreementQuery = Agreement::with(['company', 'department', 'assignedTeacher'])
            ->latest();

        if ($request->filled('status')) {
            $agreementQuery->where('status', $request->input('status'));
        }

        if ($request->filled('department_id')) {
            $agreementQuery->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('validity')) {
            switch ($request->string('validity')->toString()) {
                case 'vigentes':
                    $agreementQuery->vigentes();
                    break;
                case 'renovar_1y':
                    $agreementQuery->proximosACaducar(12);
                    break;
                case 'renovar_6m':
                    $agreementQuery->proximosACaducar(6);
                    break;
                case 'renovar_3m':
                    $agreementQuery->proximosACaducar(3);
                    break;
                case 'caducados':
                    $agreementQuery->caducados();
                    break;
            }
        }

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $agreementQuery->whereHas('company', fn ($companyQuery) => $companyQuery->where('business_name', 'like', "%{$searchTerm}%"));
        }

        return $agreementQuery;
    }

    private function normalizeCategory(?string $value): string
    {
        return match (Str::of((string) $value)->lower()->trim()->toString()) {
            'ayuntamiento' => 'ayuntamiento',
            'colegio', 'instituto', 'colegio_instituto' => 'colegio_instituto',
            'buena' => 'buena',
            'regular' => 'regular',
            default => 'funciona',
        };
    }

    private function normalizeStatus(?string $value): string
    {
        return match (Str::of((string) $value)->lower()->trim()->toString()) {
            'pendiente_generacion' => 'pendiente_generacion',
            'generado' => 'generado',
            'pendiente_firma_empresa' => 'pendiente_firma_empresa',
            'firmado_empresa' => 'firmado_empresa',
            'pendiente_firma_centro' => 'pendiente_firma_centro',
            'en_vigor', 'firmado_centro', 'activo' => 'en_vigor',
            'erroneo' => 'erroneo',
            'caducado' => 'caducado',
            default => 'borrador',
        };
    }

    private function nullIfBlank(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
