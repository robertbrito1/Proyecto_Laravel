@extends('layouts.admin')

@section('title', 'Convenio #' . $agreement->id)

@section('content')
<div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <a href="{{ route('convenios.index') }}" class="text-decoration-none text-muted small">&larr; Volver a convenios</a>
    <div class="d-flex gap-2 flex-wrap">
        @if ($canEdit && !in_array($agreement->status, ['activo','finalizado','cancelado']))
            <a href="{{ route('convenios.edit', $agreement) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
        @endif
        @if ($canSign && $agreement->status === 'pendiente_firma')
            <form method="POST" action="{{ route('convenios.sign', $agreement) }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-success">Firmar convenio</button>
            </form>
        @endif
        @if (in_array(auth()->user()->role, ['administrador','coordinadorFFE']))
            <form method="POST" action="{{ route('convenios.destroy', $agreement) }}" class="m-0"
                  onsubmit="return confirm('¿Eliminar este convenio? Esta accion no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
        @endif
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@php $badge = match($agreement->status) {
    'activo'          => 'success',
    'firmado_centro', 'firmado_empresa' => 'primary',
    'pendiente_firma' => 'warning',
    'cancelado'       => 'danger',
    'finalizado'      => 'secondary',
    'renovacion'      => 'info',
    default           => 'light text-dark',
}; @endphp

<h5 class="fw-semibold mb-3">
    Convenio #{{ $agreement->id }}
    <span class="badge text-bg-{{ $badge }} ms-2">{{ $statuses[$agreement->status] ?? $agreement->status }}</span>
</h5>

<div class="row g-3">

    {{-- Empresa --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Empresa</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Razon social</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->business_name ?? '—' }}</dd>

                    <dt class="col-sm-5">CIF</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->tax_id ?? '—' }}</dd>

                    <dt class="col-sm-5">Actividad</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->activity ?? '—' }}</dd>

                    <dt class="col-sm-5">Email</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->email ?? '—' }}</dd>

                    <dt class="col-sm-5">Tlf principal</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->main_phone ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Centro / Asignaciones --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Asignaciones IES</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Departamento</dt>
                    <dd class="col-sm-7">{{ $agreement->department?->name ?? '—' }}</dd>

                    <dt class="col-sm-5">Profesor/Tutor</dt>
                    <dd class="col-sm-7">{{ $agreement->assignedTeacher?->name ?? '—' }}</dd>

                    <dt class="col-sm-5">Tutor IES</dt>
                    <dd class="col-sm-7">{{ $agreement->iesTutor?->name ?? '—' }}</dd>

                    <dt class="col-sm-5">Fecha firma</dt>
                    <dd class="col-sm-7">{{ $agreement->signed_at?->format('d/m/Y') ?? '—' }}</dd>

                    <dt class="col-sm-5">Creado el</dt>
                    <dd class="col-sm-7">{{ $agreement->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Contacto de gestion --}}
    @if ($agreement->management_contact_name || $agreement->management_contact_email || $agreement->management_contact_phone)
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Contacto de gestion</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Nombre</dt>
                    <dd class="col-sm-7">{{ $agreement->management_contact_name ?? '—' }}</dd>

                    <dt class="col-sm-5">Telefono</dt>
                    <dd class="col-sm-7">{{ $agreement->management_contact_phone ?? '—' }}</dd>

                    <dt class="col-sm-5">Email</dt>
                    <dd class="col-sm-7">{{ $agreement->management_contact_email ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    @endif

    {{-- Notas --}}
    @if ($agreement->notes)
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Notas internas</div>
            <div class="card-body">
                <p class="mb-0" style="white-space:pre-line;">{{ $agreement->notes }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Tutores empresa --}}
    @if ($agreement->companyTutors->isNotEmpty())
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Tutores de empresa</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Cargo</th>
                            <th>Horario / Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agreement->companyTutors as $tutor)
                        <tr>
                            <td>{{ $tutor->name }} {{ $tutor->last_name_1 }} {{ $tutor->last_name_2 }}</td>
                            <td>{{ $tutor->dni ?? '—' }}</td>
                            <td>{{ $tutor->position ?? '—' }}</td>
                            <td>{{ $tutor->schedule ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Documentos --}}
    @if ($agreement->documents->isNotEmpty())
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Documentos</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Version</th>
                            <th>Estado</th>
                            <th>Subido el</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agreement->documents as $doc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>v{{ $doc->version }}</td>
                            <td><span class="badge text-bg-secondary">{{ $doc->status }}</span></td>
                            <td class="small text-muted">{{ $doc->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
