<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacuna_aplicadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mascota_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vacuna_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lote_id')->constrained('lote_vacunas')->cascadeOnDelete();
            $table->foreignId('veterinario_id')->constrained('users');
            $table->date('fecha_aplicacion');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacuna_aplicadas');
    }
};
