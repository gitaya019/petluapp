<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // primero eliminar foreign key
            $table->dropForeign(['clinica_id']);

            // luego eliminar columna
            $table->dropColumn('clinica_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('clinica_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
