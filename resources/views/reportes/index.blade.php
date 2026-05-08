{{-- Informe general de empresas y convenios exportable a Excel CSV. --}}
@extends('layouts.admin')

@section('title', 'Informes FFE')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h1 class="h4 mb-1">Informes FFE</h1>
        <p class="text-secondary mb-0">Vista global por empresa, categoría, tutor y número de convenios activos.</p>
    </div>
    <a href="{{ route('reportes.export') }}" class="btn btn-success btn-sm">Exportar a Excel CSV</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0"><div class="card-body"><div class="small text-secondary">Empresas</div><div class="fs-4 fw-bold">{{ $totals['empresas'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0"><div class="card-body"><div class="small text-secondary">Con convenio activo</div><div class="fs-4 fw-bold">{{ $totals['activas'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0"><div class="card-body"><div class="small text-secondary">Ayuntamientos</div><div class="fs-4 fw-bold">{{ $totals['ayuntamientos'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0"><div class="card-body"><div class="small text-secondary">Empresas buenas</div><div class="fs-4 fw-bold">{{ $totals['buenas'] }}</div></div></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">Vista global por empresa</div>
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Empresa</th>
                    <th>Categoría</th>
                    <th>Tutor Centro</th>
                    <th>Departamentos</th>
                    <th>Convenios activos</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row['empresa'] }}</td>
                        <td><span class="badge text-bg-light border">{{ $row['categoria'] ?: '—' }}</span></td>
                        <td>{{ $row['tutor_centro'] }}</td>
                        <td>{{ $row['departamentos'] ?: '—' }}</td>
                        <td>{{ $row['convenios_activos'] }}</td>
                        <td>{{ $row['observaciones'] ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Todavía no hay datos suficientes para generar el informe.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection