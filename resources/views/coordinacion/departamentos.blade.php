{{-- Pantalla de coordinación para consultar departamentos y responsables. --}}
@extends('layouts.admin')

@section('title', 'Coordinacion FFE - Departamentos')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-2">Gestion de departamentos</h1>
                <p class="text-secondary mb-0">Desde aqui el Coordinador FFE puede revisar los departamentos que tiene asignados y consultar sus responsables.</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        </div>
    @endif

    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Departamentos asignados</h2>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNuevoDepartamento">
                    Asignar departamento
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Departamento</th>
                            <th>Coordinador</th>
                            <th class="d-none d-md-table-cell">Tutores</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $dept)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $dept->name }}</span>
                                    @if($dept->code)
                                        <small class="text-muted d-block">{{ $dept->code }}</small>
                                    @endif
                                </td>
                                <td>{{ $dept->coordinador_name }}</td>
                                <td class="d-none d-md-table-cell text-center">
                                    <span class="badge bg-light text-dark border">{{ $dept->tutores_count }}</span>
                                </td>
                                <td>
                                    @if($dept->is_active)
                                        <span class="badge bg-success-subtle text-success-emphasis">Activo</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger-emphasis">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No hay departamentos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="h6 mb-3">Resumen</h2>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Departamentos activos</span>
                        <strong>{{ $stats['activos'] }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Tutores asignados</span>
                        <strong>{{ $stats['tutores'] }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Convenios en seguimiento</span>
                        <strong>{{ $stats['convenios'] }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Departamento -->
<div class="modal fade" id="modalNuevoDepartamento" tabindex="-1" aria-labelledby="modalNuevoDepartamentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('coordinacion.departamentos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoDepartamentoLabel">Nuevo Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Departamento</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Código (Opcional)</label>
                        <input type="text" class="form-control" id="code" name="code">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Departamento</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
