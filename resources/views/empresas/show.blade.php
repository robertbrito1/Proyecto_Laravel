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
    @if ($company->contacts->isNotEmpty())
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Contactos</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr><th scope="col">Nombre</th><th scope="col">Cargo / Tipo</th><th scope="col">Telefono</th><th scope="col">Email</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($company->contacts as $contact)
                        <tr>
                            <td>{{ $contact->name }} {{ $contact->last_name_1 }}</td>
                            <td><span class="badge text-bg-light border">{{ $contact->contact_type }}</span></td>
                            <td class="small">{{ $contact->phone ?? '—' }}</td>
                            <td class="small">{{ $contact->email ?? '—' }}</td>
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
