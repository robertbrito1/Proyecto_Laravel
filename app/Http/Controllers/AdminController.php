<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Company;

/**
 * Controlador del panel principal del administrador.
 *
 * Centraliza las métricas de resumen que se muestran en el dashboard
 * para evitar datos estáticos y reflejar el estado real del sistema.
 */
class AdminController extends Controller
{
    public function dashboard()
    {
        $vigentes           = Agreement::vigentes()->count();
        $proximosACaducar   = Agreement::proximosACaducar(12)->count();
        $pendientesFirma    = Agreement::whereIn('status', [
            'pendiente_firma_empresa',
            'pendiente_firma_centro',
        ])->count();
        $incidencias        = Agreement::where('status', 'erroneo')->count();

        $totalEmpresas      = Company::count();
        $conveniosMes       = Agreement::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $empresasMes        = Company::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $pendientes         = Agreement::whereIn('status', [
            'borrador',
            'pendiente_generacion',
            'pendiente_firma_empresa',
            'pendiente_firma_centro',
        ])->count();

        $ultimosConvenios   = Agreement::with(['company', 'assignedTeacher', 'department'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'vigentes',
            'proximosACaducar',
            'pendientesFirma',
            'incidencias',
            'totalEmpresas',
            'conveniosMes',
            'empresasMes',
            'pendientes',
            'ultimosConvenios',
        ));
    }
}
