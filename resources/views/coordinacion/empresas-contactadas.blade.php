{{-- Pantalla de coordinación para evitar duplicados en el contacto con empresas. --}}
@extends('layouts.admin')

@section('title', 'Coordinacion FFE - Empresas contactadas')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 mb-2">Empresas contactadas</h1>
                <p class="text-secondary mb-0">Busca por nombre, profesor o estado y registra cada intento de contacto para evitar duplicados.</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success mb-0">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger mb-0">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="col-12 col-xl-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-12 col-md-5">
                        <label class="form-label" for="search">Buscar empresa</label>
                        <input id="search" name="search" type="text" value="{{ request('search') }}" class="form-control" placeholder="Nombre o profesor">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="teacher_user_id">Profesor</label>
                        <select id="teacher_user_id" name="teacher_user_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_user_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="status">Estado</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Nuevo contacto</div>
            <div class="card-body">
                <form method="POST" action="{{ route('coordinacion.empresas-contactadas.store') }}" class="row g-2">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="company_id">Empresa existente</label>
                        <select id="company_id" name="company_id" class="form-select form-select-sm">
                            <option value="">Selecciona una empresa</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->business_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="company_name">O nombre manual</label>
                        <input id="company_name" name="company_name" type="text" class="form-control form-control-sm" value="{{ old('company_name') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="contact_email">Email</label>
                        <input id="contact_email" name="contact_email" type="email" class="form-control form-control-sm" value="{{ old('contact_email') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="contact_phone">Teléfono</label>
                        <input id="contact_phone" name="contact_phone" type="text" class="form-control form-control-sm" value="{{ old('contact_phone') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="contact_status">Estado</label>
                        <select id="contact_status" name="status" class="form-select form-select-sm">
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="contacted_at">Fecha</label>
                        <input id="contacted_at" name="contacted_at" type="datetime-local" class="form-control form-control-sm" value="{{ old('contacted_at') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="notes">Observaciones</label>
                        <textarea id="notes" name="notes" rows="2" class="form-control form-control-sm">{{ old('notes') }}</textarea>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-outline-primary" type="submit">Guardar contacto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Últimos contactos</h2>
                <span class="badge text-bg-light border">{{ $logs->total() }} registros</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Empresa</th>
                            <th class="d-none d-md-table-cell">Profesor</th>
                            <th class="d-none d-lg-table-cell">Contacto</th>
                            <th>Estado</th>
                            <th>Última llamada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            @php $badge = match($log->status) {
                                'contactada' => 'success',
                                'pendiente_respuesta' => 'warning',
                                default => 'danger',
                            }; @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $log->company?->business_name ?? $log->company_name }}</div>
                                    @if ($log->notes)
                                        <div class="small text-muted">{{ $log->notes }}</div>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ $log->teacher?->name ?? '—' }}</td>
                                <td class="d-none d-lg-table-cell">{{ $log->contact_email ?: '—' }} {{ $log->contact_phone ? ' / ' . $log->contact_phone : '' }}</td>
                                <td><span class="badge text-bg-{{ $badge }}">{{ $statuses[$log->status] ?? $log->status }}</span></td>
                                <td>{{ $log->contacted_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aún no hay contactos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($logs->hasPages())
        <div class="col-12">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
