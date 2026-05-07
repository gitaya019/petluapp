<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recordatorios', function (Blueprint $table) {

            // 🔗 vacuna relacionada
            $table->foreignId('vacuna_id')
                ->nullable()
                ->after('mascota_id')
                ->constrained()
                ->nullOnDelete();

            // 🔗 aplicación relacionada
            $table->foreignId('vacuna_aplicada_id')
                ->nullable()
                ->after('vacuna_id')
                ->constrained('vacuna_aplicadas')
                ->nullOnDelete();

            // 📧 trazabilidad
            $table->boolean('enviado')
                ->default(false)
                ->after('estado');

            $table->timestamp('enviado_at')
                ->nullable()
                ->after('enviado');

            $table->string('correo_destino')
                ->nullable()
                ->after('enviado_at');
        });
    }

    public function down(): void
    {
        Schema::table('recordatorios', function (Blueprint $table) {

            $table->dropForeign(['vacuna_id']);
            $table->dropForeign(['vacuna_aplicada_id']);

            $table->dropColumn([
                'vacuna_id',
                'vacuna_aplicada_id',
                'enviado',
                'enviado_at',
                'correo_destino',
            ]);
        });
    }
};