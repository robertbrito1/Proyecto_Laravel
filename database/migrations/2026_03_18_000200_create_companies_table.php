<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crea la tabla principal de empresas colaboradoras y su información legal y de contacto.

return new class extends Migration
{
    /**
     * Crea la tabla de empresas.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('tax_id')->nullable();
            $table->string('activity')->nullable();
            $table->enum('category', [
                'ayuntamiento',
                'colegio_instituto',
                'buena',
                'funciona',
                'regular',
            ])->default('funciona');
            $table->string('social_province')->nullable();
            $table->string('social_municipality')->nullable();
            $table->string('social_address')->nullable();
            $table->string('social_postal_code', 10)->nullable();
            $table->string('main_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('representative_nif')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_last_name_1')->nullable();
            $table->string('representative_last_name_2')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
