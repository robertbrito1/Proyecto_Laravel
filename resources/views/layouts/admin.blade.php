<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-body-tertiary">
@php
    $currentRole = auth()->user()?->role ?? session('role', 'invitado');
    $isAdmin = $currentRole === 'administrador';
    $isDireccion = $currentRole === 'direccion';
    $isCoordinadorFFE = $currentRole === 'coordinadorFFE';
    $roleLabels = [
        'administrador' => 'Administrador',
        'direccion' => 'Direccion',
        'coordinadorFFE' => 'Coordinador FFE',
        'tutor' => 'Tutor',
        'profesor' => 'Profesor',
        'secretaria' => 'Secretaria',
        'empresa' => 'Empresa',
        'invitado' => 'Invitado',
    ];
    $currentRoleLabel = $roleLabels[$currentRole] ?? ucfirst($currentRole);
    $homeRoute = route('login');

    if ($isAdmin) {
        $homeRoute = route('admin.dashboard');
    } elseif ($isDireccion) {
        $homeRoute = route('direccion.convenios');
    } elseif ($isCoordinadorFFE) {
        $homeRoute = route('coordinacion.departamentos');
    } elseif (in_array($currentRole, ['profesor', 'tutor'], true)) {
        $homeRoute = route('coordinacion.empresas-contactadas');
    }
@endphp
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-semibold" href="{{ $homeRoute }}">Gestion FFE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                @if ($isAdmin)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Inicio</a></li>
                @endif
                {{-- Convenios y Empresas: visibles para todos --}}
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('convenios.*') ? 'active' : '' }}" href="{{ route('convenios.index') }}">Convenios</a></li>
                @if (in_array($currentRole, ['administrador','coordinadorFFE','secretaria']))
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}" href="{{ route('empresas.index') }}">Empresas</a></li>
                @endif
                @if ($isAdmin)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}" href="{{ route('admin.usuarios') }}">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.informes') ? 'active' : '' }}" href="{{ route('admin.informes') }}">Informes</a></li>
                @endif
                @if ($isAdmin || $isCoordinadorFFE)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('coordinacion.departamentos') ? 'active' : '' }}" href="{{ route('coordinacion.departamentos') }}">Gestion de departamentos</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('coordinacion.categorias-empresas') ? 'active' : '' }}" href="{{ route('coordinacion.categorias-empresas') }}">Categorias de empresa</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('coordinacion.informes') ? 'active' : '' }}" href="{{ route('coordinacion.informes') }}">Informes FFE</a></li>
                @endif
                @if ($isAdmin || $isCoordinadorFFE || in_array($currentRole, ['profesor', 'tutor'], true))
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('coordinacion.empresas-contactadas') ? 'active' : '' }}" href="{{ route('coordinacion.empresas-contactadas') }}">Empresas contactadas</a></li>
                @endif
                @if ($isAdmin || $isDireccion)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('direccion.convenios') ? 'active' : '' }}" href="{{ route('direccion.convenios') }}">Firmar convenios</a></li>
                @endif
            </ul>
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <span class="badge text-bg-light border">Rol: {{ $currentRoleLabel }}</span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Cerrar sesion</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid px-3 px-lg-4 py-4">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
