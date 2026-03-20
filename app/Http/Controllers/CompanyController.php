<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador encargado del alta, consulta y mantenimiento de empresas.
 *
 * Reúne la lógica del módulo de empresas: listado con filtros, ficha completa,
 * formularios de edición y validación de los datos administrativos básicos.
 */
class CompanyController extends Controller
{
    /** Categorías usadas para clasificar empresas según su relación con el centro. */
    public const CATEGORIES = [
        'ayuntamiento'      => 'Ayuntamiento',
        'colegio_instituto' => 'Colegio / Instituto',
        'buena'             => 'Empresa (buena)',
        'funciona'          => 'Empresa (funciona)',
        'regular'           => 'Empresa (regular)',
    ];

    /** Roles con permiso para crear, editar o borrar información de empresas. */
    private const WRITE_ALLOWED_ROLES = ['administrador', 'coordinadorFFE', 'secretaria'];

    // ─── Listado ─────────────────────────────────────────────────────────────

    /**
     * Construye el listado paginado de empresas y aplica filtros por texto y categoría.
     */
    public function index(Request $request)
    {
        $companyQuery = Company::withCount('agreements')->latest();

        // El buscador revisa nombre, identificador fiscal y actividad principal.
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $companyQuery->where(function ($searchQuery) use ($searchTerm) {
                $searchQuery->where('business_name', 'like', "%$searchTerm%")
                    ->orWhere('tax_id', 'like', "%$searchTerm%")
                    ->orWhere('activity', 'like', "%$searchTerm%");
            });
        }

        // El filtro de categoría permite separar empresas por calidad o tipología.
        if ($request->filled('category')) {
            $companyQuery->where('category', $request->input('category'));
        }

        $companies  = $companyQuery->paginate(20)->withQueryString();
        $categories = self::CATEGORIES;

        return view('empresas.index', compact('companies', 'categories'));
    }

    // ─── Crear ───────────────────────────────────────────────────────────────

    /**
     * Prepara los catálogos necesarios para dar de alta una nueva empresa.
     */
    public function create()
    {
        $this->authorizeWrite();
        $categories = self::CATEGORIES;
        return view('empresas.create', compact('categories'));
    }

    /**
     * Valida y registra una empresa nueva en la base de datos.
     */
    public function store(Request $request)
    {
        $this->authorizeWrite();

        $data = $this->validated($request);
        $company = Company::create($data);

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Empresa registrada correctamente.');
    }

    // ─── Ver detalle ─────────────────────────────────────────────────────────

    /**
     * Muestra la ficha completa de una empresa junto con sus relaciones más usadas.
     */
    public function show(Company $company)
    {
        // Precarga relaciones para evitar consultas repetidas al pintar la vista.
        $company->load(['agreements.department', 'contacts.department', 'workCenters']);
        $categories = self::CATEGORIES;
        $canEdit = in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true);
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $contactTypes = CompanyContactController::TYPES;

        return view('empresas.show', compact('company', 'categories', 'canEdit', 'departments', 'contactTypes'));
    }

    // ─── Editar ──────────────────────────────────────────────────────────────

    /**
     * Carga el formulario de edición con los datos actuales de la empresa.
     */
    public function edit(Company $company)
    {
        $this->authorizeWrite();
        $categories = self::CATEGORIES;
        return view('empresas.edit', compact('company', 'categories'));
    }

    /**
     * Actualiza la ficha de empresa reutilizando las mismas reglas del alta.
     */
    public function update(Request $request, Company $company)
    {
        $this->authorizeWrite();
        $company->update($this->validated($request));

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Empresa actualizada correctamente.');
    }

    // ─── Eliminar ────────────────────────────────────────────────────────────

    /**
     * Elimina una empresa. Esta acción queda reservada al administrador.
     */
    public function destroy(Company $company)
    {
        abort_unless(Auth::user()->role === 'administrador', 403);
        $company->delete();

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa eliminada.');
    }

    // ─── Soporte interno ─────────────────────────────────────────────────────

    /**
     * Verifica que el rol actual puede modificar el módulo de empresas.
     */
    private function authorizeWrite(): void
    {
        abort_unless(in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true), 403);
    }

    /**
     * Reúne las reglas de validación compartidas entre creación y edición.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'business_name'              => 'required|string|max:255',
            'tax_id'                     => 'nullable|string|max:20',
            'activity'                   => 'nullable|string|max:255',
            'category'                   => 'nullable|in:' . implode(',', array_keys(self::CATEGORIES)),
            'social_province'            => 'nullable|string|max:100',
            'social_municipality'        => 'nullable|string|max:100',
            'social_address'             => 'nullable|string|max:255',
            'social_postal_code'         => 'nullable|string|max:10',
            'main_phone'                 => 'nullable|string|max:30',
            'secondary_phone'            => 'nullable|string|max:30',
            'email'                      => 'nullable|email|max:255',
            'representative_nif'         => 'nullable|string|max:20',
            'representative_name'        => 'nullable|string|max:100',
            'representative_last_name_1' => 'nullable|string|max:100',
            'representative_last_name_2' => 'nullable|string|max:100',
            'notes'                      => 'nullable|string|max:2000',
        ]);
    }
}
