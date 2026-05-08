{{-- Vista de detalle de un convenio con acciones según permisos y estado. --}}
@extends('layouts.admin')

@section('title', 'Convenio #' . $agreement->id)

@section('content')
<div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <a href="{{ route('convenios.index') }}" class="text-decoration-none text-muted small">&larr; Volver a convenios</a>
    <div class="d-flex gap-2 flex-wrap">
        @if ($canEdit)
            <a href="{{ route('convenios.edit', $agreement) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
        @endif
        @if ($canSign && $agreement->status === 'pendiente_firma_centro')
            <form method="POST" action="{{ route('convenios.sign', $agreement) }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-success">Firmar por el centro</button>
            </form>
        @endif
        @if (in_array(auth()->user()->role, ['administrador','coordinadorFFE']))
            <form method="POST" action="{{ route('convenios.destroy', $agreement) }}" class="m-0" onsubmit="return confirm('¿Eliminar este convenio? Esta accion no se puede deshacer.')">
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

@php
    $statusBadgeClass = match($agreement->status) {
        'en_vigor' => 'success',
        'firmado_empresa', 'pendiente_firma_centro' => 'primary',
        'pendiente_generacion', 'generado', 'pendiente_firma_empresa' => 'warning',
        'erroneo', 'caducado' => 'danger',
        default => 'secondary',
    };
@endphp

<h5 class="fw-semibold mb-3">
    Convenio #{{ $agreement->id }}
    <span class="badge text-bg-{{ $statusBadgeClass }} ms-2">{{ $statuses[$agreement->status] ?? $agreement->status }}</span>
    <span class="badge text-bg-light border ms-2">{{ $agreement->validity_label }}</span>
</h5>

<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Empresa</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Razón social</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->business_name ?? '—' }}</dd>

                    <dt class="col-sm-5">CIF</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->tax_id ?? '—' }}</dd>

                    <dt class="col-sm-5">Actividad</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->activity ?? '—' }}</dd>

                    <dt class="col-sm-5">Email</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->email ?? '—' }}</dd>

                    <dt class="col-sm-5">Teléfono</dt>
                    <dd class="col-sm-7">{{ $agreement->company?->main_phone ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

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

                    <dt class="col-sm-5">Caduca el</dt>
                    <dd class="col-sm-7">{{ $agreement->expires_at ? date('d/m/Y', strtotime($agreement->expires_at)) : '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    @if ($agreement->management_contact_name || $agreement->management_contact_email || $agreement->management_contact_phone)
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Contacto de gestión</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Nombre</dt>
                    <dd class="col-sm-7">{{ $agreement->management_contact_name ?? '—' }}</dd>

                    <dt class="col-sm-5">Teléfono</dt>
                    <dd class="col-sm-7">{{ $agreement->management_contact_phone ?? '—' }}</dd>

                    <dt class="col-sm-5">Email</dt>
                    <dd class="col-sm-7">{{ $agreement->management_contact_email ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    @endif

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

    @if ($agreement->companyTutors->isNotEmpty())
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Tutores de empresa</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">DNI</th>
                            <th scope="col">Horario por defecto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agreement->companyTutors as $tutor)
                        <tr>
                            <td>{{ $tutor->full_name ?? '—' }}</td>
                            <td>{{ $tutor->dni ?? '—' }}</td>
                            <td>{{ $tutor->default_schedule ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12 col-xl-5">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Historial de documentos PDF</div>
            <div class="card-body">
                <p class="text-secondary small">Cada subida crea una versión nueva y conserva el estado anterior para trazabilidad.</p>

                @if ($canManageDocuments)
                    <form method="POST" action="{{ route('convenios.documents.store', $agreement) }}" enctype="multipart/form-data" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <label for="document" class="form-label">PDF del convenio</label>
                            <input type="file" id="document" name="document" accept="application/pdf" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label for="document_status" class="form-label">Estado del documento</label>
                            <select id="document_status" name="document_status" class="form-select form-select-sm" required>
                                @foreach ($documentStatuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="error_reason" class="form-label">Motivo de error si corresponde</label>
                            <textarea id="error_reason" name="error_reason" class="form-control form-control-sm" rows="2" placeholder="Describe la incidencia si el documento es erróneo"></textarea>
                        </div>
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">Guardar nueva versión</button>
                        </div>
                    </form>
                @else
                    <p class="text-muted mb-0">Tu perfil solo puede consultar el histórico documental.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-7">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Versiones registradas</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Versión</th>
                            <th>Estado</th>
                            <th>Motivo</th>
                            <th>Subido por</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agreement->documents as $doc)
                        <tr>
                            <td>v{{ $doc->version }}</td>
                            <td><span class="badge text-bg-secondary">{{ $documentStatuses[$doc->status] ?? $doc->status }}</span></td>
                            <td>{{ $doc->error_reason ?: '—' }}</td>
                            <td>{{ $doc->uploadedBy?->name ?? 'Sistema' }}</td>
                            <td class="small text-muted">{{ $doc->uploaded_at?->format('d/m/Y H:i') ?? $doc->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('convenios.documents.download', [$agreement, $doc]) }}" class="btn btn-sm btn-outline-primary">Descargar</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Todavía no hay documentos subidos para este convenio.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
