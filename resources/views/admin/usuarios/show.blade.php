{{-- Vista de detalle de un usuario con acciones de edición, activación y eliminación. --}}
@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <a href="{{ route('admin.usuarios.index') }}" class="text-decoration-none text-muted small">&larr; Volver a usuarios</a>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Editar</a>

        {{-- Activar / Desactivar --}}
        @if ($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.usuarios.toggle', $user) }}" class="m-0">
                @csrf
                @method('PATCH')
                @if ($user->is_active)
                    <button type="submit" class="btn btn-sm btn-outline-warning"
                            onclick="return confirm('¿Desactivar a {{ $user->name }}? No podra iniciar sesion.')">Desactivar</button>
                @else
                    <button type="submit" class="btn btn-sm btn-outline-success">Activar</button>
                @endif
            </form>

            {{-- Eliminar --}}
            <form method="POST" action="{{ route('admin.usuarios.destroy', $user) }}" class="m-0"
                  onsubmit="return confirm('¿Eliminar definitivamente a {{ $user->name }}? Esta accion no se puede deshacer.')">
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
    {{ $user->name }}
    @if ($user->is_active)
        <span class="badge text-bg-success ms-2">Activo</span>
    @else
        <span class="badge text-bg-secondary ms-2">Inactivo</span>
    @endif
</h5>

<div class="row g-3">

    {{-- Datos de la cuenta --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Datos de la cuenta</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Email</dt>
                    <dd class="col-sm-7">{{ $user->email }}</dd>

                    <dt class="col-sm-5">Telefono</dt>
                    <dd class="col-sm-7">{{ $user->phone ?? '—' }}</dd>

                    <dt class="col-sm-5">Rol</dt>
                    <dd class="col-sm-7">
                        <span class="badge text-bg-info">{{ $roles[$user->role] ?? ucfirst($user->role) }}</span>
                    </dd>

                    <dt class="col-sm-5">Departamento</dt>
                    <dd class="col-sm-7">{{ $user->department?->name ?? '—' }}</dd>

                    <dt class="col-sm-5">Registrado</dt>
                    <dd class="col-sm-7">{{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>

                    <dt class="col-sm-5">Ultimo acceso</dt>
                    <dd class="col-sm-7">{{ $user->email_verified_at?->format('d/m/Y H:i') ?? 'Sin verificar' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Resumen de actividad --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Actividad relacionada</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-7">Convenios creados</dt>
                    <dd class="col-sm-5">
                        <span class="badge text-bg-light border">{{ $user->createdAgreements()->count() }}</span>
                    </dd>

                    <dt class="col-sm-7">Convenios asignados</dt>
                    <dd class="col-sm-5">
                        <span class="badge text-bg-light border">{{ $user->assignedAgreements()->count() }}</span>
                    </dd>

                    <dt class="col-sm-7">Empresas vinculadas</dt>
                    <dd class="col-sm-5">
                        <span class="badge text-bg-light border">{{ $user->companies()->count() }}</span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

</div>
@endsection
