{{-- Vista para editar los datos de un usuario existente. --}}
@extends('layouts.admin')

@section('title', 'Editar usuario')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.usuarios.show', $user) }}" class="text-decoration-none text-muted small">&larr; Volver al usuario</a>
</div>

<div class="card shadow-sm" style="max-width:750px;">
    <div class="card-header fw-semibold">Editar: {{ $user->name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.usuarios.update', $user) }}">
            @csrf
            @method('PUT')
            @include('admin.usuarios._form', ['user' => $user])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                <a href="{{ route('admin.usuarios.show', $user) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
