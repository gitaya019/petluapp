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
        Schema::create('lote_vacunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vacuna_id')->constrained()->cascadeOnDelete();
            $table->string('numero_lote');
            $table->date('fecha_vencimiento');
            $table->integer('stock_inicial');
            $table->integer('stock_actual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote_vacunas');
    }
};
