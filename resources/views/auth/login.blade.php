<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-body-tertiary">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="row g-0">
                        <section class="col-12 col-md-6 bg-primary-subtle p-4 p-lg-5 d-flex flex-column justify-content-center">
                            <p class="text-primary fw-semibold text-uppercase small mb-2">Bienvenido</p>
                            <h1 class="h3 fw-bold mb-3">Ingresa con tu cuenta</h1>
                            <p class="text-secondary mb-4">
                                Escribe tu usuario y contraseña para entrar.
                            </p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent px-0">Acceso rápido y claro</li>
                                <li class="list-group-item bg-transparent px-0">Compatible con inicio por Google</li>
                                <li class="list-group-item bg-transparent px-0">Diseño responsive</li>
                            </ul>
                        </section>

                        <section class="col-12 col-md-6 p-4 p-lg-5">
                            <h2 class="h4 mb-2">Iniciar sesion</h2>
                            <p class="text-secondary mb-4">Entra con tu usuario o con Google.</p>

                            <form method="POST" action="#" onsubmit="return false;" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Usuario o correo</label>
                                    <input id="username" name="username" type="text" class="form-control" placeholder="ejemplo@dominio.com" autocomplete="username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="********" autocomplete="current-password" required>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">Entrar</button>

                                    <div class="d-flex align-items-center text-body-secondary small">
                                        <hr class="flex-grow-1 m-0">
                                        <span class="px-2">o</span>
                                        <hr class="flex-grow-1 m-0">
                                    </div>

                                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                                        <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                                            <path fill="#EA4335" d="M24 9.5c3.65 0 6.93 1.26 9.52 3.72l7.1-7.1C36.3 2.17 30.6 0 24 0 14.64 0 6.53 5.4 2.6 13.28l8.28 6.43C12.86 13.12 17.98 9.5 24 9.5Z"/>
                                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.14-3.08-.4-4.55H24v8.62h12.9c-.55 2.96-2.23 5.46-4.73 7.13l7.25 5.62c4.22-3.88 6.56-9.59 6.56-16.82Z"/>
                                            <path fill="#FBBC05" d="M10.88 28.29A14.5 14.5 0 0 1 10.1 24c0-1.48.26-2.91.78-4.29l-8.28-6.43A23.98 23.98 0 0 0 0 24c0 3.86.92 7.5 2.6 10.72l8.28-6.43Z"/>
                                            <path fill="#34A853" d="M24 48c6.6 0 12.15-2.17 16.2-5.9l-7.25-5.62c-2.02 1.36-4.6 2.17-8.95 2.17-6.02 0-11.14-3.62-13.12-8.79l-8.28 6.43C6.53 42.6 14.64 48 24 48Z"/>
                                        </svg>
                                        Continuar con Google
                                    </button>
                                </div>
                            </form>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
