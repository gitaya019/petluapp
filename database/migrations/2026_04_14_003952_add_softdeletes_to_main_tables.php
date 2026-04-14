<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'ventas',
            'users',
            'clinicas',
            'mascotas',
            'vacunas',
            'vacuna_aplicadas',
            'historial_medicos',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'ventas',
            'users',
            'clinicas',
            'mascotas',
            'vacunas',
            'vacuna_aplicadas',
            'historial_medicos',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
