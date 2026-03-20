<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyContactController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas web principales del panel, autenticación y módulos de empresas y convenios.
// La organización por bloques refleja los perfiles del sistema y sus áreas de trabajo.

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

// Panel genérico para roles que aún no tienen una vista funcional propia.
Route::view('/panel', 'panel.home')
    ->middleware('role')
    ->name('panel.home');

// Bloque exclusivo del administrador con accesos generales del sistema.
Route::middleware('role:administrador')->group(function () {
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
    Route::redirect('/admin/convenios', '/convenios')->name('admin.convenios');
    Route::redirect('/admin/empresas', '/empresas')->name('admin.empresas');
    Route::view('/admin/informes', 'admin.informes')->name('admin.informes');

    // ── CRUD de usuarios ──────────────────────────────────────────────────
    Route::get('/admin/usuarios',                  [UserController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/usuarios/nuevo',            [UserController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios',                 [UserController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{user}',           [UserController::class, 'show'])->name('admin.usuarios.show');
    Route::get('/admin/usuarios/{user}/editar',    [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{user}',           [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::patch('/admin/usuarios/{user}/toggle',  [UserController::class, 'toggleActive'])->name('admin.usuarios.toggle');
    Route::delete('/admin/usuarios/{user}',        [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
});

// Vistas de coordinación orientadas a consulta y organización operativa.
Route::view('/coordinacion/departamentos', 'coordinacion.departamentos')
    ->middleware('role:coordinadorFFE,administrador')
    ->name('coordinacion.departamentos');

Route::view('/coordinacion/categorias-empresas', 'coordinacion.categorias-empresas')
    ->middleware('role:coordinadorFFE,administrador')
    ->name('coordinacion.categorias-empresas');

Route::view('/coordinacion/empresas-contactadas', 'coordinacion.empresas-contactadas')
    ->middleware('role:coordinadorFFE,administrador,profesor,tutor')
    ->name('coordinacion.empresas-contactadas');

Route::view('/coordinacion/informes', 'coordinacion.informes')
    ->middleware('role:coordinadorFFE,administrador')
    ->name('coordinacion.informes');

Route::get('/direccion/convenios', function () {
    // Dirección entra directamente al listado ya filtrado por convenios pendientes de firma.
    return redirect()->route('convenios.index', ['status' => 'pendiente_firma']);
})->middleware('role:direccion,administrador')->name('direccion.convenios');

// ── CRUD de empresas ──────────────────────────────────────────────────────
// Además del CRUD principal, este bloque incluye la gestión de contactos por empresa.
Route::middleware('role')->group(function () {
    Route::get('/empresas',                  [CompanyController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/nueva',            [CompanyController::class, 'create'])->name('empresas.create');
    Route::post('/empresas',                 [CompanyController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/{company}',        [CompanyController::class, 'show'])->name('empresas.show');
    Route::get('/empresas/{company}/editar', [CompanyController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{company}',        [CompanyController::class, 'update'])->name('empresas.update');
    Route::delete('/empresas/{company}',     [CompanyController::class, 'destroy'])->name('empresas.destroy');

    Route::post('/empresas/{company}/contactos', [CompanyContactController::class, 'store'])
        ->name('empresas.contactos.store');
    Route::put('/empresas/{company}/contactos/{contact}', [CompanyContactController::class, 'update'])
        ->name('empresas.contactos.update');
    Route::delete('/empresas/{company}/contactos/{contact}', [CompanyContactController::class, 'destroy'])
        ->name('empresas.contactos.destroy');
});

// ── CRUD de convenios ─────────────────────────────────────────────────────
// Este bloque cubre consulta, mantenimiento y firma de convenios.
Route::middleware('role')->group(function () {
    Route::get('/convenios',                [AgreementController::class, 'index'])->name('convenios.index');
    Route::get('/convenios/nuevo',          [AgreementController::class, 'create'])->name('convenios.create');
    Route::post('/convenios',               [AgreementController::class, 'store'])->name('convenios.store');
    Route::get('/convenios/{agreement}',    [AgreementController::class, 'show'])->name('convenios.show');
    Route::get('/convenios/{agreement}/editar', [AgreementController::class, 'edit'])->name('convenios.edit');
    Route::put('/convenios/{agreement}',    [AgreementController::class, 'update'])->name('convenios.update');
    Route::delete('/convenios/{agreement}', [AgreementController::class, 'destroy'])->name('convenios.destroy');
    Route::post('/convenios/{agreement}/firmar', [AgreementController::class, 'sign'])->name('convenios.sign');
});
