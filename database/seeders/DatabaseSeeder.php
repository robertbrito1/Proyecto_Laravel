<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $informatica = Department::updateOrCreate(
            ['name' => 'Informatica'],
            ['code' => 'INF', 'is_active' => true],
        );

        $sanidad = Department::updateOrCreate(
            ['name' => 'Sanidad'],
            ['code' => 'SAN', 'is_active' => true],
        );

        $users = [
            [
                'name' => 'Admin FFE',
                'email' => 'admin@ffe.local',
                'role' => 'administrador',
                'department_id' => $informatica->id,
                'phone' => '600000001',
            ],
            [
                'name' => 'Direccion Centro',
                'email' => 'direccion@ffe.local',
                'role' => 'direccion',
                'department_id' => null,
                'phone' => '600000002',
            ],
            [
                'name' => 'Coordinacion FFE',
                'email' => 'coordinacion@ffe.local',
                'role' => 'coordinadorFFE',
                'department_id' => $informatica->id,
                'phone' => '600000003',
            ],
            [
                'name' => 'Tutor DAM',
                'email' => 'tutor@ffe.local',
                'role' => 'tutor',
                'department_id' => $informatica->id,
                'phone' => '600000004',
            ],
            [
                'name' => 'Profesor FFE',
                'email' => 'profesor@ffe.local',
                'role' => 'profesor',
                'department_id' => $informatica->id,
                'phone' => '600000005',
            ],
            [
                'name' => 'Secretaria FFE',
                'email' => 'secretaria@ffe.local',
                'role' => 'secretaria',
                'department_id' => null,
                'phone' => '600000006',
            ],
            [
                'name' => 'Empresa Externa',
                'email' => 'empresa@ffe.local',
                'role' => 'empresa',
                'department_id' => $sanidad->id,
                'phone' => '600000007',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => $data['role'],
                    'department_id' => $data['department_id'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
