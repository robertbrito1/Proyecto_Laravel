@extends('layouts.admin')

@section('title', $company->business_name)

@section('content')
<div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <a href="{{ route('empresas.index') }}" class="text-decoration-none text-muted small">&larr; Volver a empresas</a>
    <div class="d-flex gap-2 flex-wrap">
        @if ($canEdit)
            <a href="{{ route('empresas.edit', $company) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
        @endif
        @if (auth()->user()->role === 'administrador')
            <form method="POST" action="{{ route('empresas.destroy', $company) }}" class="m-0"
                  onsubmit="return confirm('¿Eliminar esta empresa? Se eliminaran tambien sus datos asociados.')">
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

<h5 class="fw-semibold mb-3">
    {{ $company->business_name }}
    @if ($company->category)
        <span class="badge text-bg-secondary ms-2">{{ $categories[$company->category] ?? $company->category }}</span>
    @endif
</h5>

<div class="row g-3">

    {{-- Datos generales --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Datos generales</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">CIF / NIF</dt>
                    <dd class="col-sm-7">{{ $company->tax_id ?? '—' }}</dd>

                    <dt class="col-sm-5">Actividad</dt>
                    <dd class="col-sm-7">{{ $company->activity ?? '—' }}</dd>

                    <dt class="col-sm-5">Email</dt>
                    <dd class="col-sm-7">{{ $company->email ?? '—' }}</dd>

                    <dt class="col-sm-5">Tlf principal</dt>
                    <dd class="col-sm-7">{{ $company->main_phone ?? '—' }}</dd>

                    <dt class="col-sm-5">Tlf secundario</dt>
                    <dd class="col-sm-7">{{ $company->secondary_phone ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Domicilio + Representante --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Domicilio social</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Direccion</dt>
                    <dd class="col-sm-7">{{ $company->social_address ?? '—' }}</dd>

                    <dt class="col-sm-5">Municipio</dt>
                    <dd class="col-sm-7">{{ $company->social_municipality ?? '—' }}</dd>

                    <dt class="col-sm-5">Provincia</dt>
                    <dd class="col-sm-7">{{ $company->social_province ?? '—' }}</dd>

                    <dt class="col-sm-5">Cod. postal</dt>
                    <dd class="col-sm-7">{{ $company->social_postal_code ?? '—' }}</dd>
                </dl>
                @if ($company->representative_name)
                    <hr>
                    <p class="mb-1 small text-muted fw-semibold text-uppercase">Representante legal</p>
                    <p class="mb-0">
                        {{ $company->representative_name }}
                        {{ $company->representative_last_name_1 }}
                        {{ $company->representative_last_name_2 }}
                        @if ($company->representative_nif)
                            <span class="text-muted small">({{ $company->representative_nif }})</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Notas --}}
    @if ($company->notes)
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Notas internas</div>
            <div class="card-body">
                <p class="mb-0" style="white-space:pre-line;">{{ $company->notes }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Convenios de esta empresa --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold">Convenios</span>
                @if ($canEdit)
                    <a href="{{ route('convenios.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-primary">+ Nuevo convenio</a>
                @endif
            </div>
            @if ($company->agreements->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha</th>
                            <th scope="col"><span class="visually-hidden">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($company->agreements as $ag)
                        <tr>
                            <td class="text-muted small">{{ $ag->id }}</td>
                            <td>{{ $ag->department?->name ?? '—' }}</td>
                            <td><span class="badge text-bg-secondary">{{ $ag->status }}</span></td>
                            <td class="small text-muted">{{ $ag->created_at->format('d/m/Y') }}</td>
                            <td><a href="{{ route('convenios.show', $ag) }}" class="btn btn-sm btn-outline-primary">Ver</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="card-body text-muted small">Sin convenios registrados.</div>
            @endif
        </div>
    </div>

    {{-- Contactos --}}
    <div class="col-12" id="contactos">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold">Contactos</span>
                <span class="badge text-bg-light border">{{ $company->contacts->count() }}</span>
            </div>

            @if ($canEdit)
            <div class="card-body border-bottom bg-body-tertiary">
                <h6 class="mb-3">Agregar contacto</h6>
                <form method="POST" action="{{ route('empresas.contactos.store', $company) }}" class="row g-2">
                    @csrf
                    <div class="col-12 col-md-4">
                        <label class="form-label small">Nombre completo *</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small">Tipo *</label>
                        <select name="type" class="form-select form-select-sm" required>
                            @foreach ($contactTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small">Departamento</label>
                        <select name="department_id" class="form-select form-select-sm">
                            <option value="">Sin departamento</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">NIF</label>
                        <input type="text" name="nif" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small">Telefono 1</label>
                        <input type="text" name="phone_1" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small">Telefono 2</label>
                        <input type="text" name="phone_2" class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label small">Notas</label>
                        <input type="text" name="notes" class="form-control form-control-sm" maxlength="1500">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-primary">Agregar contacto</button>
                    </div>
                </form>
            </div>
            @endif

            @if ($company->contacts->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Departamento</th>
                                <th>Telefonos</th>
                                <th>Email</th>
                                <th>Notas</th>
                                @if ($canEdit)
                                    <th style="width: 320px;">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->contacts as $contact)
                                <tr>
                                    <td>{{ $contact->full_name }}</td>
                                    <td><span class="badge text-bg-light border">{{ $contactTypes[$contact->type] ?? $contact->type }}</span></td>
                                    <td>{{ $contact->department?->name ?? '—' }}</td>
                                    <td class="small">
                                        {{ $contact->phone_1 ?? '—' }}
                                        @if ($contact->phone_2)
                                            <br>{{ $contact->phone_2 }}
                                        @endif
                                    </td>
                                    <td class="small">{{ $contact->email ?? '—' }}</td>
                                    <td class="small">{{ $contact->notes ?? '—' }}</td>
                                    @if ($canEdit)
                                        <td>
                                            <details>
                                                <summary class="small">Editar</summary>
                                                <form method="POST" action="{{ route('empresas.contactos.update', [$company, $contact]) }}" class="mt-2 d-grid gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="full_name" class="form-control form-control-sm" value="{{ $contact->full_name }}" required>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <select name="type" class="form-select form-select-sm" required>
                                                                @foreach ($contactTypes as $key => $label)
                                                                    <option value="{{ $key }}" {{ $contact->type === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <select name="department_id" class="form-select form-select-sm">
                                                                <option value="">Sin departamento</option>
                                                                @foreach ($departments as $department)
                                                                    <option value="{{ $department->id }}" {{ $contact->department_id === $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-6"><input type="text" name="nif" class="form-control form-control-sm" value="{{ $contact->nif }}" placeholder="NIF"></div>
                                                        <div class="col-6"><input type="text" name="phone_1" class="form-control form-control-sm" value="{{ $contact->phone_1 }}" placeholder="Telefono 1"></div>
                                                        <div class="col-6"><input type="text" name="phone_2" class="form-control form-control-sm" value="{{ $contact->phone_2 }}" placeholder="Telefono 2"></div>
                                                        <div class="col-6"><input type="email" name="email" class="form-control form-control-sm" value="{{ $contact->email }}" placeholder="Email"></div>
                                                    </div>
                                                    <input type="text" name="notes" class="form-control form-control-sm" value="{{ $contact->notes }}" placeholder="Notas">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Guardar</button>
                                                </form>
                                                <form method="POST" action="{{ route('empresas.contactos.destroy', [$company, $contact]) }}" class="mt-2" onsubmit="return confirm('¿Eliminar contacto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                                </form>
                                            </details>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card-body text-muted small">Sin contactos registrados.</div>
            @endif
        </div>
    </div>

</div>
@endsection
