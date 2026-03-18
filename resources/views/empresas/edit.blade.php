@extends('layouts.admin')

@section('title', 'Editar empresa')

@section('content')
<div class="mb-3">
    <a href="{{ route('empresas.show', $company) }}" class="text-decoration-none text-muted small">&larr; Volver a la empresa</a>
</div>

<div class="card shadow-sm" style="max-width:900px;">
    <div class="card-header fw-semibold">Editar: {{ $company->business_name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('empresas.update', $company) }}">
            @csrf
            @method('PUT')
            @include('empresas._form', ['company' => $company])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Actualizar empresa</button>
                <a href="{{ route('empresas.show', $company) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
