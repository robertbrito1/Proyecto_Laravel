<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crea la tabla de tutores de empresa asociados a cada convenio.

return new class extends Migration
{
    /**
     * Crea la tabla de tutores empresariales por convenio.
     */
    public function up(): void
    {
        Schema::create('agreement_company_tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('dni')->nullable();
            $table->string('default_schedule')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreement_company_tutors');
    }
};
