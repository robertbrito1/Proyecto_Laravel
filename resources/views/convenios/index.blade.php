{{-- Listado filtrable de convenios con acceso a alta, edición y consulta. --}}
@extends('layouts.admin')

@section('title', 'Convenios')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <h4 class="mb-0 fw-semibold">Convenios</h4>
    @if (in_array(auth()->user()->role, ['administrador','coordinadorFFE','secretaria','tutor','profesor']))
        <a href="{{ route('convenios.create') }}" class="btn btn-primary btn-sm">+ Nuevo convenio</a>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('convenios.index') }}" class="row g-2 mb-3" role="search" aria-label="Filtrar convenios">
    <div class="col-12 col-md-4">
        <label for="filter-search" class="visually-hidden">Buscar empresa</label>
        <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar empresa...">
    </div>
    <div class="col-6 col-md-3">
        <label for="filter-status" class="visually-hidden">Estado</label>
        <select id="filter-status" name="status" class="form-select form-select-sm">
            <option value="">Todos los estados</option>
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-3">
        <label for="filter-department" class="visually-hidden">Departamento</label>
        <select id="filter-department" name="department_id" class="form-select form-select-sm">
            <option value="">Todos los departamentos</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Filtrar</button>
        <a href="{{ route('convenios.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Limpiar</a>
    </div>
</form>

{{-- Tabla --}}
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" aria-label="Listado de convenios">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Profesor/Tutor</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha alta</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agreements as $agreementItem)
                    <tr>
                        <td class="text-muted small">{{ $agreementItem->id }}</td>
                        <td>{{ $agreementItem->company?->business_name ?? '—' }}</td>
                        <td>{{ $agreementItem->department?->name ?? '—' }}</td>
                        <td>{{ $agreementItem->assignedTeacher?->name ?? '—' }}</td>
                        <td>
                            {{-- Bootstrap badge color based on agreement status --}}
                            @php $statusBadgeClass = match($agreementItem->status) {
                                'activo'          => 'success',
                                'firmado_centro', 'firmado_empresa' => 'primary',
                                'pendiente_firma' => 'warning',
                                'cancelado'       => 'danger',
                                'finalizado'      => 'secondary',
                                'renovacion'      => 'info',
                                default           => 'light text-dark',
                            }; @endphp
                            <span class="badge text-bg-{{ $statusBadgeClass }}">{{ $statuses[$agreementItem->status] ?? $agreementItem->status }}</span>
                        </td>
                        <td class="small text-muted">{{ $agreementItem->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('convenios.show', $agreementItem) }}" class="btn btn-sm btn-outline-primary"
                               aria-label="Ver convenio #{{ $agreementItem->id }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay convenios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($agreements->hasPages())
    <div class="mt-3">
        {{ $agreements->links() }}
    </div>
@endif
@endsection
