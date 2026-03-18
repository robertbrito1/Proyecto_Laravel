<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/login', 'auth.login')->name('login');

Route::middleware('role:administrador')->group(function () {
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/admin/convenios', 'admin.convenios')->name('admin.convenios');
    Route::view('/admin/empresas', 'admin.empresas')->name('admin.empresas');
    Route::view('/admin/usuarios', 'admin.usuarios')->name('admin.usuarios');
    Route::view('/admin/informes', 'admin.informes')->name('admin.informes');
});

Route::view('/direccion/convenios', 'direccion.convenios')
    ->middleware('role:direccion,administrador')
    ->name('direccion.convenios');

Route::get('/demo/rol/{role}', function (Request $request, string $role) {
    $allowedRoles = [
        'administrador',
        'direccion',
        'coordinador',
        'tutor',
        'profesor',
        'secretaria',
        'empresa',
    ];

    abort_unless(in_array($role, $allowedRoles, true), 404);

    $request->session()->put('role', $role);

    return redirect()->back();
})->name('demo.role');
