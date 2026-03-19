@extends('layouts.admin')

@section('title', 'Panel Administrador')

@section('content')
<div class="row g-4">
    <aside class="col-12 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary mb-3">Menu rapido</h2>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.convenios') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Convenios en vigor
                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill">128</span>
                    </a>
                    <a href="{{ route('admin.convenios') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Por caducar
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">19</span>
                    </a>
                    <a href="{{ route('admin.convenios') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Pendientes de firma
                        <span class="badge bg-info-subtle text-info-emphasis rounded-pill">11</span>
                    </a>
                    <a href="{{ route('admin.convenios') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Incidencias
                        <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">4</span>
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
                        <p class="small text-secondary mb-1">Empresas activas</p>
                        <p class="h4 mb-0">74</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Convenios este mes</p>
                        <p class="h4 mb-0">23</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Empresas nuevas</p>
                        <p class="h4 mb-0">8</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="small text-secondary mb-1">Tareas pendientes</p>
                        <p class="h4 mb-0">15</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-2">
                <h3 class="h6 mb-0">Ultimos convenios actualizados</h3>
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.informes') }}">Exportar Excel</a>
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.convenios') }}">Nuevo convenio</a>
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
                    <tr>
                        <td>Alfa Sistemas S.L.</td>
                        <td class="d-none d-md-table-cell">Marta Perez</td>
                        <td class="d-none d-lg-table-cell">Informatica</td>
                        <td><span class="badge bg-success-subtle text-success-emphasis">En vigor</span></td>
                        <td>Hoy 09:40</td>
                    </tr>
                    <tr>
                        <td>Clinica Norte</td>
                        <td class="d-none d-md-table-cell">Carlos Romero</td>
                        <td class="d-none d-lg-table-cell">Sanidad</td>
                        <td><span class="badge bg-warning-subtle text-warning-emphasis">Pendiente firma empresa</span></td>
                        <td>Ayer 18:10</td>
                    </tr>
                    <tr>
                        <td>Ayuntamiento de Mostoles</td>
                        <td class="d-none d-md-table-cell">Laura Gomez</td>
                        <td class="d-none d-lg-table-cell">Administracion</td>
                        <td><span class="badge bg-info-subtle text-info-emphasis">Pendiente firma centro</span></td>
                        <td>18/03/2026</td>
                    </tr>
                    <tr>
                        <td>TecnoRed S.A.</td>
                        <td class="d-none d-md-table-cell">Ana Moreno</td>
                        <td class="d-none d-lg-table-cell">Informatica</td>
                        <td><span class="badge bg-danger-subtle text-danger-emphasis">Erroneo</span></td>
                        <td>17/03/2026</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
