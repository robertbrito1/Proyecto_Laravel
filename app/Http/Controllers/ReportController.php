<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Genera vistas y exportaciones de informes para empresas y convenios.
 */
class ReportController extends Controller
{
    public function index()
    {
        $rows = Company::with(['agreements.assignedTeacher', 'agreements.department'])
            ->orderBy('business_name')
            ->get()
            ->map(function (Company $company) {
                $firstAgreement = $company->agreements->first();
                $activeCount = $company->agreements->filter(function ($agreement) {
                    return $agreement->signed_at && $agreement->signed_at->gte(now()->subYears(4));
                })->count();

                return [
                    'empresa' => $company->business_name,
                    'categoria' => $company->category,
                    'tutor_centro' => $firstAgreement?->assignedTeacher?->name ?? '—',
                    'departamentos' => $company->agreements->pluck('department.name')->filter()->unique()->implode(', '),
                    'convenios_activos' => $activeCount,
                    'observaciones' => Str::limit((string) ($company->notes ?? ''), 90),
                ];
            });

        $totals = [
            'empresas' => $rows->count(),
            'activas' => $rows->where('convenios_activos', '>', 0)->count(),
            'ayuntamientos' => $rows->where('categoria', 'ayuntamiento')->count(),
            'buenas' => $rows->where('categoria', 'buena')->count(),
        ];

        return view('reportes.index', compact('rows', 'totals'));
    }

    public function exportCsv(): StreamedResponse
    {
        $rows = Company::with(['agreements.assignedTeacher', 'agreements.department'])
            ->orderBy('business_name')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['EMPRESA', 'CATEGORIA', 'TUTOR CENTRO', 'DEPARTAMENTOS', 'CONVENIOS ACTIVOS', 'OBSERVACIONES'], ';');

            foreach ($rows as $company) {
                $firstAgreement = $company->agreements->first();
                $activeCount = $company->agreements->filter(function ($agreement) {
                    return $agreement->signed_at && $agreement->signed_at->gte(now()->subYears(4));
                })->count();

                fputcsv($handle, [
                    $company->business_name,
                    $company->category,
                    $firstAgreement?->assignedTeacher?->name ?? '',
                    $company->agreements->pluck('department.name')->filter()->unique()->implode(', '),
                    $activeCount,
                    $company->notes ?? '',
                ], ';');
            }

            fclose($handle);
        }, 'informe_empresas_ffe.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
