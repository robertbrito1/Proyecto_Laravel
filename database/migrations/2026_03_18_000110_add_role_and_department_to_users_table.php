<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Amplía la tabla de usuarios con rol, departamento y datos de activación del panel.

return new class extends Migration
{
    /**
     * Añade a usuarios los campos necesarios para permisos y organización interna.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->enum('role', [
                'administrador',
                'direccion',
                'coordinadorFFE',
                'tutor',
                'profesor',
                'secretaria',
                'empresa',
            ])->default('profesor')->after('department_id');
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn(['role', 'phone', 'is_active']);
        });
    }
};
