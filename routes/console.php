<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Comandos de consola simples disponibles en el proyecto.

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Muestra una frase inspiradora');
