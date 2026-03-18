@extends('layouts.admin')

@section('title', 'Nuevo convenio')

@section('content')
<div class="mb-3">
    <a href="{{ route('convenios.index') }}" class="text-decoration-none text-muted small">&larr; Volver a convenios</a>
</div>

<div class="card shadow-sm" style="max-width:750px;">
    <div class="card-header fw-semibold">Nuevo convenio</div>
    <div class="card-body">
        <form method="POST" action="{{ route('convenios.store') }}">
            @csrf
            @include('convenios._form', ['agreement' => null])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Guardar convenio</button>
                <a href="{{ route('convenios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
