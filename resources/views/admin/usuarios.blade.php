{{-- Vista informativa del administrador orientada a la futura gestión de usuarios. --}}
@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-1">Usuarios</h1>
                <p class="text-secondary mb-0">Aqui podras administrar cuentas, roles y permisos de acceso.</p>
            </div>
        </div>
    </div>
</div>
@endsection
