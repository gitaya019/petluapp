<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('clinica_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('mascota_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('veterinario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('vacuna_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->date('fecha');

            $table->time('hora');

            $table->enum('estado', [
                'pendiente',
                'confirmada',
                'completada',
                'cancelada',
                'no_asistio',
            ])->default('pendiente');

            $table->text('motivo')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};