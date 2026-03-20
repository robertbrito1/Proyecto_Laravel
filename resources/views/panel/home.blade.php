{{-- Pantalla genérica de inicio para perfiles sin panel específico desarrollado. --}}
@extends('layouts.admin')

@section('title', 'Panel')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h4 mb-2">Panel de usuario</h1>
                <p class="text-secondary mb-3">Has iniciado sesion correctamente.</p>
                <p class="mb-0">Tu rol actual es <strong>{{ auth()->user()->role }}</strong>. Desde aqui iremos montando las opciones especificas de este perfil.</p>
            </div>
        </div>
    </div>
</div>
@endsection
