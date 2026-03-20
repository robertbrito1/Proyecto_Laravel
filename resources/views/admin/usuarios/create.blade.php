{{-- Vista para registrar un nuevo usuario en el sistema. --}}
@extends('layouts.admin')

@section('title', 'Nuevo usuario')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.usuarios.index') }}" class="text-decoration-none text-muted small">&larr; Volver a usuarios</a>
</div>

<div class="card shadow-sm" style="max-width:750px;">
    <div class="card-header fw-semibold">Nuevo usuario</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.usuarios.store') }}">
            @csrf
            @include('admin.usuarios._form', ['user' => null])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Crear usuario</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
