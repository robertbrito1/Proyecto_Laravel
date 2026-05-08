{{-- Panel principal del administrador con accesos rápidos y resumen del área. --}}
@extends('layouts.admin')

@section('title', 'Panel Administrador')

@section('content')
@php
    $statusBadgeMap = [
        'en_vigor'                => ['class' => 'success',   'label' => 'En vigor'],
        'firmado_centro'          => ['class' => 'success',   'label' => 'En vigor'],
        'pendiente_firma_empresa' => ['class' => 'warning',   'label' => 'Pendiente firma empresa'],
        'firmado_empresa'         => ['class' => 'warning',   'label' => 'Firmado por empresa'],
        'pendiente_firma_centro'  => ['class' => 'info',      'label' => 'Pendiente firma centro'],
        'pendiente_generacion'    => ['class' => 'secondary', 'label' => 'Pendiente generación'],
        'generado'                => ['class' => 'secondary', 'label' => 'Generado'],
        'borrador'                => ['class' => 'secondary', 'label' => 'Borrador'],
        'erroneo'                 => ['class' => 'danger',    'label' => 'Erróneo'],
        'caducado'                => ['class' => 'danger',    'label' => 'Caducado'],
    ];
@endphp
<div class="row g-4">
    <aside class="col-12 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary mb-3">Menu rapido</h2>
                <div class="list-group list-group-flush">
                    <a href="{{ route('convenios.index', ['validity' => 'vigentes']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Convenios en vigor
                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill">{{ $vigentes }}</span>
                    </a>
                    <a href="{{ route('convenios.index', ['validity' => 'renovar_1y']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Por caducar
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">{{ $proximosACaducar }}</span>
                    </a>
                    <a href="{{ route('convenios.index', ['status' => 'pendiente_firma_centro']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Pendientes de firma
                        <span class="badge bg-info-subtle text-info-emphasis rounded-pill">{{ $pendientesFirma }}</span>
                    </a>
                    <a href="{{ route('convenios.index', ['status' => 'erroneo']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Incidencias
                        <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">{{ $incidencias }}</span>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <section class="col-12 col-lg-9">
        <div class="row g-3 mb-2">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Empresas registradas</p>
                        <p class="h4 mb-0">{{ $totalEmpresas }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Convenios este mes</p>
                        <p class="h4 mb-0">{{ $conveniosMes }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Empresas este mes</p>
                        <p class="h4 mb-0">{{ $empresasMes }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Pendientes de tramitar</p>
                        <p class="h4 mb-0">{{ $pendientes }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-2">
                <h3 class="h6 mb-0">Últimos convenios actualizados</h3>
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.informes') }}">Exportar Excel</a>
                    <a class="btn btn-sm btn-primary" href="{{ route('convenios.create') }}">Nuevo convenio</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Empresa</th>
                        <th scope="col" class="d-none d-md-table-cell">Tutor</th>
                        <th scope="col" class="d-none d-lg-table-cell">Departamento</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Actualizado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($ultimosConvenios as $convenio)
                        @php
                            $badge = $statusBadgeMap[$convenio->status] ?? ['class' => 'secondary', 'label' => $convenio->status];
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('convenios.show', $convenio) }}" class="text-decoration-none">
                                    {{ $convenio->company?->business_name ?? '—' }}
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $convenio->assignedTeacher?->name ?? '—' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $convenio->department?->name ?? '—' }}</td>
                            <td><span class="badge bg-{{ $badge['class'] }}-subtle text-{{ $badge['class'] }}-emphasis">{{ $badge['label'] }}</span></td>
                            <td>{{ $convenio->updated_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay convenios registrados aún.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
