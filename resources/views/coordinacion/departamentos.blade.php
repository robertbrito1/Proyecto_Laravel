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

    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Departamentos asignados</h2>
                <button class="btn btn-sm btn-primary" type="button">Asignar departamento</button>
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
                        <tr>
                            <td>Informatica</td>
                            <td>Lucia Martin</td>
                            <td class="d-none d-md-table-cell">4</td>
                            <td><span class="badge bg-success-subtle text-success-emphasis">Activo</span></td>
                        </tr>
                        <tr>
                            <td>Sanidad</td>
                            <td>Rafael Perez</td>
                            <td class="d-none d-md-table-cell">3</td>
                            <td><span class="badge bg-success-subtle text-success-emphasis">Activo</span></td>
                        </tr>
                        <tr>
                            <td>Administracion</td>
                            <td>Elena Rubio</td>
                            <td class="d-none d-md-table-cell">2</td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis">Revision</span></td>
                        </tr>
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
                        <strong>3</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Tutores asignados</span>
                        <strong>9</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Convenios en seguimiento</span>
                        <strong>27</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
