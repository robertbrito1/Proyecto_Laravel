{{-- Pantalla de coordinación destinada a informes y futuras exportaciones. --}}
@extends('layouts.admin')

@section('title', 'Coordinacion FFE - Informes')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-2">Informes</h1>
                <p class="text-secondary mb-0">Informes pensados para mostrar por pantalla y exportar despues a Excel.</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Vista global por familia</h2>
                <p class="text-secondary small">Empresa, tutor del centro, ciclos relacionados y observaciones.</p>
                <button type="button" class="btn btn-outline-secondary btn-sm">Ver informe</button>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Vista por ciclos</h2>
                <p class="text-secondary small">Filtra por dos ciclos concretos, por ejemplo DAM y DAW.</p>
                <button type="button" class="btn btn-outline-secondary btn-sm">Ver informe</button>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Vista por curso</h2>
                <p class="text-secondary small">Muestra empresas por primer o segundo curso dentro de la familia.</p>
                <button type="button" class="btn btn-outline-secondary btn-sm">Ver informe</button>
            </div>
        </div>
    </div>

    <div class="col-12 mt-1">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Ejemplo de salida</h2>
                <button type="button" class="btn btn-sm btn-primary">Exportar a Excel</button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Empresa</th>
                            <th>Tutor Centro</th>
                            <th>DAM</th>
                            <th>DAW</th>
                            <th>ASIR</th>
                            <th>SMR</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Alfa Sistemas</td>
                            <td>Marta Perez</td>
                            <td>2</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>Buena respuesta en seguimiento</td>
                        </tr>
                        <tr>
                            <td>TecnoRed</td>
                            <td>Ana Moreno</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>Revisar renovacion en seis meses</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
