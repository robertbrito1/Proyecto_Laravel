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
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('empresas.index') }}" class="row g-2 mb-3" role="search" aria-label="Filtrar empresas">
    <div class="col-12 col-md-5">
        <label for="filter-search" class="visually-hidden">Buscar empresa</label>
        <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar por nombre, CIF o actividad...">
    </div>
    <div class="col-6 col-md-3">
        <label for="filter-category" class="visually-hidden">Categoría</label>
        <select id="filter-category" name="category" class="form-select form-select-sm">
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
        <table class="table table-hover align-middle mb-0" aria-label="Listado de empresas">
            <thead class="table-light">
                <tr>
                    <th scope="col">Razon social</th>
                    <th scope="col">CIF</th>
                    <th scope="col">Actividad</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Convenios</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $companyItem)
                    <tr>
                        <td class="fw-semibold">{{ $companyItem->business_name }}</td>
                        <td class="small text-muted">{{ $companyItem->tax_id ?? '—' }}</td>
                        <td class="small">{{ $companyItem->activity ?? '—' }}</td>
                        <td>
                            @if ($companyItem->category)
                                <span class="badge text-bg-secondary">{{ $categories[$companyItem->category] ?? $companyItem->category }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="small">{{ $companyItem->main_phone ?? '—' }}</td>
                        <td>
                            <span class="badge text-bg-light border">{{ $companyItem->agreements_count }}</span>
                        </td>
                        <td>
                            <a href="{{ route('empresas.show', $companyItem) }}" class="btn btn-sm btn-outline-primary"
                               aria-label="Ver empresa {{ $companyItem->business_name }}">Ver</a>
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
