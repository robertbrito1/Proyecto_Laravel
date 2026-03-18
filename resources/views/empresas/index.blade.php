@extends('layouts.admin')

@section('title', 'Empresas')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <h4 class="mb-0 fw-semibold">Empresas</h4>
    @if (in_array(auth()->user()->role, ['administrador','coordinadorFFE','secretaria']))
        <a href="{{ route('empresas.create') }}" class="btn btn-primary btn-sm">+ Nueva empresa</a>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('empresas.index') }}" class="row g-2 mb-3">
    <div class="col-12 col-md-5">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar por nombre, CIF o actividad...">
    </div>
    <div class="col-6 col-md-3">
        <select name="category" class="form-select form-select-sm">
            <option value="">Todas las categorias</option>
            @foreach ($categories as $key => $label)
                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Filtrar</button>
        <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Limpiar</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Razon social</th>
                    <th>CIF</th>
                    <th>Actividad</th>
                    <th>Categoria</th>
                    <th>Telefono</th>
                    <th>Convenios</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $co)
                    <tr>
                        <td class="fw-semibold">{{ $co->business_name }}</td>
                        <td class="small text-muted">{{ $co->tax_id ?? '—' }}</td>
                        <td class="small">{{ $co->activity ?? '—' }}</td>
                        <td>
                            @if ($co->category)
                                <span class="badge text-bg-secondary">{{ $categories[$co->category] ?? $co->category }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="small">{{ $co->main_phone ?? '—' }}</td>
                        <td>
                            <span class="badge text-bg-light border">{{ $co->agreements_count }}</span>
                        </td>
                        <td>
                            <a href="{{ route('empresas.show', $co) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay empresas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($companies->hasPages())
    <div class="mt-3">{{ $companies->links() }}</div>
@endif
@endsection
