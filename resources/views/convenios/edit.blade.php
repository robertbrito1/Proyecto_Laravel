@extends('layouts.admin')

@section('title', 'Editar convenio')

@section('content')
<div class="mb-3">
    <a href="{{ route('convenios.show', $agreement) }}" class="text-decoration-none text-muted small">&larr; Volver al convenio</a>
</div>

<div class="card shadow-sm" style="max-width:750px;">
    <div class="card-header fw-semibold">Editar convenio #{{ $agreement->id }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('convenios.update', $agreement) }}">
            @csrf
            @method('PUT')
            @include('convenios._form', ['agreement' => $agreement])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Actualizar convenio</button>
                <a href="{{ route('convenios.show', $agreement) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
