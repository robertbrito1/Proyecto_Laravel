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
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('convenios.index') }}" class="row g-2 mb-3">
    <div class="col-12 col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar empresa...">
    </div>
    <div class="col-6 col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">Todos los estados</option>
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-3">
        <select name="department_id" class="form-select form-select-sm">
            <option value="">Todos los departamentos</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
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
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Departamento</th>
                    <th>Profesor/Tutor</th>
                    <th>Estado</th>
                    <th>Fecha alta</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agreements as $ag)
                    <tr>
                        <td class="text-muted small">{{ $ag->id }}</td>
                        <td>{{ $ag->company?->business_name ?? '—' }}</td>
                        <td>{{ $ag->department?->name ?? '—' }}</td>
                        <td>{{ $ag->assignedTeacher?->name ?? '—' }}</td>
                        <td>
                            @php $badge = match($ag->status) {
                                'activo'          => 'success',
                                'firmado_centro', 'firmado_empresa' => 'primary',
                                'pendiente_firma' => 'warning',
                                'cancelado'       => 'danger',
                                'finalizado'      => 'secondary',
                                'renovacion'      => 'info',
                                default           => 'light text-dark',
                            }; @endphp
                            <span class="badge text-bg-{{ $badge }}">{{ $statuses[$ag->status] ?? $ag->status }}</span>
                        </td>
                        <td class="small text-muted">{{ $ag->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('convenios.show', $ag) }}" class="btn btn-sm btn-outline-primary">Ver</a>
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
