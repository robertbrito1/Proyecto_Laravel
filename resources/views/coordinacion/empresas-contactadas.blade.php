{{-- Pantalla de coordinación para evitar duplicados en el contacto con empresas. --}}
@extends('layouts.admin')

@section('title', 'Coordinacion FFE - Empresas contactadas')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-2">Empresas contactadas</h1>
                <p class="text-secondary mb-0">Registro de llamadas y contactos para evitar duplicados y saber quien lleva cada empresa.</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="search">Buscar empresa</label>
                        <input id="search" type="text" class="form-control" placeholder="Nombre, email o telefono">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="profesor">Profesor</label>
                        <select id="profesor" class="form-select">
                            <option selected>Todos</option>
                            <option>Ana Moreno</option>
                            <option>Lucia Martin</option>
                            <option>Carlos Romero</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="estado">Estado</label>
                        <select id="estado" class="form-select">
                            <option selected>Todos</option>
                            <option>Contactada</option>
                            <option>Pendiente respuesta</option>
                            <option>Descartada</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Ultimos contactos</h2>
                <button class="btn btn-sm btn-primary" type="button">Nuevo contacto</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Empresa</th>
                            <th class="d-none d-md-table-cell">Profesor</th>
                            <th class="d-none d-lg-table-cell">Contacto</th>
                            <th>Estado</th>
                            <th>Ultima llamada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Softline Madrid</td>
                            <td class="d-none d-md-table-cell">Ana Moreno</td>
                            <td class="d-none d-lg-table-cell">info@softline.es / 916001122</td>
                            <td><span class="badge bg-info-subtle text-info-emphasis">Pendiente respuesta</span></td>
                            <td>Hoy 10:20</td>
                        </tr>
                        <tr>
                            <td>IES Europa</td>
                            <td class="d-none d-md-table-cell">Lucia Martin</td>
                            <td class="d-none d-lg-table-cell">direccion@ieseuropa.es / 918002233</td>
                            <td><span class="badge bg-success-subtle text-success-emphasis">Contactada</span></td>
                            <td>Ayer</td>
                        </tr>
                        <tr>
                            <td>Clinica Dental Sur</td>
                            <td class="d-none d-md-table-cell">Carlos Romero</td>
                            <td class="d-none d-lg-table-cell">gestion@dental-sur.es / 917334455</td>
                            <td><span class="badge bg-danger-subtle text-danger-emphasis">Descartada</span></td>
                            <td>17/03/2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
