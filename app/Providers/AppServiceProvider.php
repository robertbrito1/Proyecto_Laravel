<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider principal para registrar y arrancar servicios globales de la aplicación.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios o bindings disponibles en toda la aplicación.
     */
    public function register(): void
    {
        // Aquí se registrarían servicios propios del proyecto si fueran necesarios.
        //
    }

    /**
        * Ejecuta lógica de arranque cuando la aplicación ya está inicializada.
     */
    public function boot(): void
    {
        // Aquí se centralizaría cualquier configuración de arranque compartida.
        //
    }
}
