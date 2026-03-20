<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador que administra los contactos asociados a cada empresa.
 */
class CompanyContactController extends Controller
{
    /** Tipos de contacto disponibles para clasificar cada persona vinculada a la empresa. */
    public const TYPES = [
        'principal'      => 'Principal',
        'familia'        => 'Familia profesional',
        'representante'  => 'Representante',
        'tutor_empresa'  => 'Tutor de empresa',
    ];

    /** Roles con permiso para modificar la agenda de contactos. */
    private const WRITE_ALLOWED_ROLES = ['administrador', 'coordinadorFFE', 'secretaria'];

    public function store(Request $request, Company $company)
    {
        $this->authorizeWrite();

        $validatedData = $this->validated($request);
        $validatedData['company_id'] = $company->id;

        CompanyContact::create($validatedData);

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Contacto agregado correctamente.');
    }

    public function update(Request $request, Company $company, CompanyContact $contact)
    {
        $this->authorizeWrite();
        // Impide modificar contactos que pertenecen a otra empresa.
        abort_unless($contact->company_id === $company->id, 404);

        $contact->update($this->validated($request));

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Contacto actualizado correctamente.');
    }

    public function destroy(Company $company, CompanyContact $contact)
    {
        $this->authorizeWrite();
        // Impide eliminar contactos que pertenecen a otra empresa.
        abort_unless($contact->company_id === $company->id, 404);

        $contact->delete();

        return redirect()->route('empresas.show', $company)
            ->with('success', 'Contacto eliminado.');
    }

    private function authorizeWrite(): void
    {
        abort_unless(in_array(Auth::user()->role, self::WRITE_ALLOWED_ROLES, true), 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'type'          => 'required|in:' . implode(',', array_keys(self::TYPES)),
            'full_name'     => 'required|string|max:255',
            'nif'           => 'nullable|string|max:20',
            'phone_1'       => 'nullable|string|max:30',
            'phone_2'       => 'nullable|string|max:30',
            'email'         => 'nullable|email|max:255',
            'notes'         => 'nullable|string|max:1500',
        ]);
    }
}
