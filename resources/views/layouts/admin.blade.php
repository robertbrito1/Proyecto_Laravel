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
    $currentRole = session('role', 'invitado');
    $isAdmin = $currentRole === 'administrador';
    $isDireccion = $currentRole === 'direccion';
    $homeRoute = $isDireccion && ! $isAdmin ? route('direccion.convenios') : route('admin.dashboard');
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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.convenios') ? 'active' : '' }}" href="{{ route('admin.convenios') }}">Convenios</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.empresas') ? 'active' : '' }}" href="{{ route('admin.empresas') }}">Empresas</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}" href="{{ route('admin.usuarios') }}">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.informes') ? 'active' : '' }}" href="{{ route('admin.informes') }}">Informes</a></li>
                @endif
                @if ($isAdmin || $isDireccion)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('direccion.convenios') ? 'active' : '' }}" href="{{ route('direccion.convenios') }}">Firmar convenios</a></li>
                @endif
            </ul>
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <span class="badge text-bg-light border">Rol: {{ ucfirst($currentRole) }}</span>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Cerrar sesion</a>
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
