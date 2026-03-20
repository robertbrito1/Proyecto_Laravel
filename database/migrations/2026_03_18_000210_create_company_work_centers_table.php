<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crea la tabla de centros de trabajo vinculados a cada empresa.

return new class extends Migration
{
    /**
     * Crea la tabla de centros de trabajo de empresa.
     */
    public function up(): void
    {
        Schema::create('company_work_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('province');
            $table->string('municipality');
            $table->string('address');
            $table->string('postal_code', 10)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_work_centers');
    }
};
