<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crea la tabla de convenios con estado, responsables y datos de seguimiento.

return new class extends Migration
{
    /**
     * Crea la tabla principal de convenios.
     */
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ies_tutor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('management_contact_name')->nullable();
            $table->string('management_contact_phone')->nullable();
            $table->string('management_contact_email')->nullable();
            $table->date('signed_at')->nullable();
            $table->enum('status', [
                'borrador',
                'pendiente_generacion',
                'generado',
                'pendiente_firma_empresa',
                'firmado_empresa',
                'pendiente_firma_centro',
                'en_vigor',
                'erroneo',
                'caducado',
            ])->default('borrador');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
