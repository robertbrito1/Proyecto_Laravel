{{-- Listado filtrable de convenios con acceso a alta, importación, exportación y consulta. --}}
@extends('layouts.admin')

@section('title', 'Convenios')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <h4 class="mb-0 fw-semibold">Convenios</h4>
        <p class="text-secondary small mb-0">Control de vigencia, firma y trazabilidad documental.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('convenios.export', request()->query()) }}" class="btn btn-outline-success btn-sm">Exportar Excel CSV</a>
        @if (in_array(auth()->user()->role, ['administrador','coordinadorFFE','secretaria','tutor','profesor']))
            <a href="{{ route('convenios.create') }}" class="btn btn-primary btn-sm">+ Nuevo convenio</a>
        @endif
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3 mb-3">
    <div class="col-6 col-lg">
        <div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="small text-secondary">En vigor</div><div class="fs-4 fw-bold">{{ $summary['vigentes'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="small text-secondary">Renovar &lt; 1 año</div><div class="fs-4 fw-bold">{{ $summary['renovar_1y'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="small text-secondary">Renovar &lt; 6 meses</div><div class="fs-4 fw-bold">{{ $summary['renovar_6m'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="small text-secondary">Renovar &lt; 3 meses</div><div class="fs-4 fw-bold">{{ $summary['renovar_3m'] }}</div></div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="small text-secondary">Caducados</div><div class="fs-4 fw-bold text-danger">{{ $summary['caducados'] }}</div></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-lg-7">
                <form method="GET" action="{{ route('convenios.index') }}" class="row g-2" role="search" aria-label="Filtrar convenios">
                    <div class="col-12 col-md-4">
                        <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar empresa...">
                    </div>
                    <div class="col-6 col-md-3">
                        <select id="filter-status" name="status" class="form-select form-select-sm">
                            <option value="">Todos los estados</option>
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <select id="filter-department" name="department_id" class="form-select form-select-sm">
                            <option value="">Todos los departamentos</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <select name="validity" class="form-select form-select-sm">
                            <option value="">Toda vigencia</option>
                            @foreach ($validityOptions as $key => $label)
                                <option value="{{ $key }}" {{ request('validity') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Filtrar</button>
                        <a href="{{ route('convenios.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Limpiar</a>
                    </div>
                </form>
            </div>
            <div class="col-12 col-lg-5">
                <form method="POST" action="{{ route('convenios.import') }}" enctype="multipart/form-data" class="row g-2">
                    @csrf
                    <div class="col-12">
                        <label for="import_file" class="form-label small mb-1">Importar convenios desde Excel guardado como CSV</label>
                        <input id="import_file" type="file" name="import_file" accept=".csv,text/csv" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Importar archivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                    <th scope="col">Vigencia</th>
                    <th scope="col">Alta</th>
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
                            @php $statusBadgeClass = match($agreementItem->status) {
                                'en_vigor' => 'success',
                                'firmado_empresa', 'pendiente_firma_centro' => 'primary',
                                'pendiente_generacion', 'generado', 'pendiente_firma_empresa' => 'warning',
                                'erroneo', 'caducado' => 'danger',
                                default => 'secondary',
                            }; @endphp
                            <span class="badge text-bg-{{ $statusBadgeClass }}">{{ $statuses[$agreementItem->status] ?? $agreementItem->status }}</span>
                        </td>
                        <td><span class="badge text-bg-light border">{{ $agreementItem->validity_label }}</span></td>
                        <td class="small text-muted">{{ $agreementItem->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('convenios.show', $agreementItem) }}" class="btn btn-sm btn-outline-primary" aria-label="Ver convenio #{{ $agreementItem->id }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay convenios registrados.</td>
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
