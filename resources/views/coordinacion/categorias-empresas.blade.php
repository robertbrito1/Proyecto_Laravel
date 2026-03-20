{{-- Pantalla de coordinación para revisar la clasificación de las empresas. --}}
@extends('layouts.admin')

@section('title', 'Coordinacion FFE - Categorias de empresas')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-2">Categorias de empresas</h1>
                <p class="text-secondary mb-0">Aqui puedes ver como esta clasificada cada empresa y que tipo de contacto requiere segun la familia profesional.</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Listado de categorias</h2>
                <button class="btn btn-sm btn-primary" type="button">Nueva categoria</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Categoria</th>
                            <th>Color</th>
                            <th class="d-none d-md-table-cell">Tipo de contacto</th>
                            <th>Empresas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ayuntamiento</td>
                            <td><span class="badge rounded-pill text-bg-primary">Institucional</span></td>
                            <td class="d-none d-md-table-cell">Un unico contacto para todo el centro</td>
                            <td>6</td>
                        </tr>
                        <tr>
                            <td>Colegios / Institutos</td>
                            <td><span class="badge rounded-pill text-bg-secondary">Academico</span></td>
                            <td class="d-none d-md-table-cell">Contacto principal por familia</td>
                            <td>14</td>
                        </tr>
                        <tr>
                            <td>Empresas buenas</td>
                            <td><span class="badge rounded-pill bg-success">Verde</span></td>
                            <td class="d-none d-md-table-cell">Un contacto por cada familia relacionada</td>
                            <td>21</td>
                        </tr>
                        <tr>
                            <td>Empresas que funcionan</td>
                            <td><span class="badge rounded-pill bg-warning text-dark">Amarillo</span></td>
                            <td class="d-none d-md-table-cell">Relacion estable con seguimiento</td>
                            <td>18</td>
                        </tr>
                        <tr>
                            <td>Empresas regulares</td>
                            <td><span class="badge rounded-pill bg-danger">Rojo</span></td>
                            <td class="d-none d-md-table-cell">Se usan solo cuando hace falta</td>
                            <td>7</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Criterio rapido</h2>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">Las empresas verdes son prioritarias para asignar alumnado.</li>
                    <li class="list-group-item px-0">Las amarillas requieren seguimiento normal.</li>
                    <li class="list-group-item px-0">Las rojas deben revisarse antes de volver a contactar.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
