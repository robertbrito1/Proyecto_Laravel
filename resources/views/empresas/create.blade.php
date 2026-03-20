{{-- Vista para registrar una nueva empresa con el formulario compartido. --}}
@extends('layouts.admin')

@section('title', 'Nueva empresa')

@section('content')
<div class="mb-3">
    <a href="{{ route('empresas.index') }}" class="text-decoration-none text-muted small">&larr; Volver a empresas</a>
</div>

<div class="card shadow-sm" style="max-width:900px;">
    <div class="card-header fw-semibold">Nueva empresa</div>
    <div class="card-body">
        <form method="POST" action="{{ route('empresas.store') }}">
            @csrf
            @include('empresas._form', ['company' => null])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Guardar empresa</button>
                <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
