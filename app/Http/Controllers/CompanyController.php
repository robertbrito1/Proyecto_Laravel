<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public const CATEGORIES = [
        'ayuntamiento'      => 'Ayuntamiento',
        'colegio_instituto' => 'Colegio / Instituto',
        'buena'             => 'Empresa (buena)',
        'funciona'          => 'Empresa (funciona)',
        'regular'           => 'Empresa (regular)',
    ];

    private const CAN_WRITE = ['administrador', 'coordinadorFFE', 'secretaria'];

    // ─── List ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Company::withCount('agreements')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('business_name', 'like', "%$s%")
                  ->orWhere('tax_id', 'like', "%$s%")
                  ->orWhere('activity', 'like', "%$s%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $companies  = $query->paginate(20)->withQueryString();
        $categories = self::CATEGORIES;

        return view('empresas.index', compact('companies', 'categories'));
    }

    // ─── Create ──────────────────────────────────────────────────────────────

    public function create()
    {
        $this->authorizeWrite();
        $categories = self::CATEGORIES;
        return view('empresas.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeWrite();

        $data = $this->validated($request);
        $company = Company::create($data);

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Empresa registrada correctamente.');
    }

    // ─── Show ────────────────────────────────────────────────────────────────

    public function show(Company $company)
    {
        $company->load(['agreements.department', 'contacts', 'workCenters']);
        $categories = self::CATEGORIES;
        $canEdit    = in_array(Auth::user()->role, self::CAN_WRITE);

        return view('empresas.show', compact('company', 'categories', 'canEdit'));
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(Company $company)
    {
        $this->authorizeWrite();
        $categories = self::CATEGORIES;
        return view('empresas.edit', compact('company', 'categories'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeWrite();
        $company->update($this->validated($request));

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Empresa actualizada correctamente.');
    }

    // ─── Delete ──────────────────────────────────────────────────────────────

    public function destroy(Company $company)
    {
        abort_unless(Auth::user()->role === 'administrador', 403);
        $company->delete();

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa eliminada.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeWrite(): void
    {
        abort_unless(in_array(Auth::user()->role, self::CAN_WRITE), 403);
    }

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
