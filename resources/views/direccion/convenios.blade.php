@extends('layouts.admin')

@section('title', 'Direccion - Firma de Convenios')

@section('content')
@php
    $role = session('role', 'invitado');
    $canSign = $role === 'direccion';
@endphp

<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-2">Firma de convenios (Direccion)</h1>
                <p class="text-secondary mb-0">En esta vista solo se firma por parte del centro. No se permite editar otros datos.</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-2">
                <h2 class="h6 mb-0">Pendientes de firma del centro</h2>
                <span class="badge bg-primary-subtle text-primary-emphasis">Rol actual: {{ ucfirst($role) }}</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Empresa</th>
                            <th class="d-none d-md-table-cell">Tutor</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <!-- poner un bucle con los convenios que se tengan -->
                            <td>Alfa Sistemas S.L.</td>
                            <td class="d-none d-md-table-cell">Marta Perez</td>
                            <td><span class="badge bg-info-subtle text-info-emphasis">Pendiente firma centro</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="acciones convenio">
                                    <button class="btn btn-outline-secondary m-2" type="button">Descargar PDF</button>
                                    <button class="btn btn-success m-2" type="button" {{ $canSign ? '' : 'disabled' }}>Firmar convenio</button>
                                    <button class="btn btn-outline-danger m-2" type="button" {{ $canSign ? '' : 'disabled' }}>Marcar incorrecto</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Clinica Norte</td>
                            <td class="d-none d-md-table-cell">Carlos Romero</td>
                            <td><span class="badge bg-info-subtle text-info-emphasis">Pendiente firma centro</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="acciones convenio">
                                    <button class="btn btn-outline-secondary m-2" type="button">Descargar PDF</button>
                                    <button class="btn btn-success m-2" type="button" {{ $canSign ? '' : 'disabled' }}>Firmar convenio</button>
                                    <button class="btn btn-outline-danger m-2" type="button" {{ $canSign ? '' : 'disabled' }}>Marcar incorrecto</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
