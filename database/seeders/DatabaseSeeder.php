<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Seeder principal que carga usuarios y departamentos desde un script SQL externo.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $driver = DB::connection()->getDriverName();
        $sqlFile = match ($driver) {
            'mysql', 'mariadb' => database_path('sql/usuarios_iniciales_mysql.sql'),
            'sqlsrv' => database_path('sql/usuarios_iniciales_sqlserver.sql'),
            default => database_path('sql/usuarios_iniciales_mysql.sql'),
        };

        if (! File::exists($sqlFile)) {
            $this->command?->warn('No se encontró el archivo SQL de usuarios iniciales.');
            return;
        }

        DB::unprepared(File::get($sqlFile));

        $this->command?->info('Datos iniciales importados desde el archivo SQL.');
    }
}
