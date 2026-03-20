{{-- Listado de usuarios con filtros por nombre, rol y estado de activación. --}}
@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <h4 class="mb-0 fw-semibold">Usuarios</h4>
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-sm">+ Nuevo usuario</a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-2 mb-3" role="search" aria-label="Filtrar usuarios">
    <div class="col-12 col-md-4">
        <label for="filter-search" class="visually-hidden">Buscar usuario</label>
        <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar por nombre, email o telefono...">
    </div>
    <div class="col-6 col-md-2">
        <label for="filter-role" class="visually-hidden">Rol</label>
        <select id="filter-role" name="role" class="form-select form-select-sm">
            <option value="">Todos los roles</option>
            @foreach ($roles as $key => $label)
                <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-2">
        <label for="filter-active" class="visually-hidden">Estado</label>
        <select id="filter-active" name="is_active" class="form-select form-select-sm">
            <option value="">Todos</option>
            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Activos</option>
            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactivos</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Filtrar</button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Limpiar</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" aria-label="Listado de usuarios">
            <thead class="table-light">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Estado</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $userItem)
                    <tr class="{{ !$userItem->is_active ? 'table-secondary' : '' }}">
                        <td class="fw-semibold">{{ $userItem->name }}</td>
                        <td class="small text-muted">{{ $userItem->email }}</td>
                        <td>
                            <span class="badge text-bg-info">{{ $roles[$userItem->role] ?? ucfirst($userItem->role) }}</span>
                        </td>
                        <td class="small">{{ $userItem->department?->name ?? '—' }}</td>
                        <td>
                            @if ($userItem->is_active)
                                <span class="badge text-bg-success">Activo</span>
                            @else
                                <span class="badge text-bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.usuarios.show', $userItem) }}" class="btn btn-sm btn-outline-primary"
                               aria-label="Ver usuario {{ $userItem->name }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($users->hasPages())
    <div class="mt-3">{{ $users->links() }}</div>
@endif
@endsection
