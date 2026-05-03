<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mascota;
use App\Models\Recordatorio;
use App\Models\Vacuna;
use App\Models\VacunaAplicada;
use App\Models\LoteVacuna;
use App\Models\HistorialMedico;
use App\Models\Clinica;
use Faker\Factory as Faker;
use Carbon\Carbon;

class FakeClinicaDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $clinicas = [1, 13];

        foreach ($clinicas as $clinicaId) {

            // =========================
            // 👤 USERS (10 por clínica)
            // =========================
            $users = [];

            for ($i = 0; $i < 10; $i++) {
                $users[] = User::create([
                    'name' => $faker->name(),
                    'email' => $faker->unique()->safeEmail(),
                    'password' => Hash::make('password'),
                    'numero_documento' => $faker->unique()->numerify('##########'),
                    'tipo_documento' => 'CC',
                    'telefono' => $faker->numerify('3#########'),
                    'estado' => 1,
                    'is_super_admin' => 0,
                ]);
            }

            // =========================
            // 🐶 MASCOTAS (10 por clínica)
            // =========================
            $mascotas = [];

            for ($i = 0; $i < 10; $i++) {
                $mascotas[] = Mascota::create([
                    'clinica_id' => $clinicaId,
                    'user_id' => $users[array_rand($users)]->id,
                    'nombre' => $faker->firstName(),
                    'especie' => $faker->randomElement(['Perro', 'Gato']),
                    'raza' => $faker->word(),
                    'fecha_nacimiento' => $faker->date(),
                    'sexo' => $faker->randomElement(['Macho', 'Hembra']),
                    'peso' => $faker->randomFloat(2, 1, 40),
                    'color' => $faker->safeColorName(),
                    'estado' => 1,
                ]);
            }

            // =========================
            // 💉 VACUNAS (10 por clínica)
            // =========================
            $vacunas = [];

            for ($i = 0; $i < 10; $i++) {
                $vacunas[] = Vacuna::create([
                    'clinica_id' => $clinicaId,
                    'nombre' => $faker->word(),
                    'descripcion' => $faker->sentence(),
                    'dosis' => '1 ml',
                    'fabricante' => $faker->company(),
                    'estado' => 1,
                    'dias_refuerzo' => rand(30, 365),
                    'precio_dosis' => rand(10000, 80000),
                ]);
            }

            // =========================
            // 📦 LOTES VACUNAS
            // =========================
            $lotes = [];

            foreach ($vacunas as $vacuna) {
                $lotes[] = LoteVacuna::create([
                    'clinica_id' => $clinicaId,
                    'vacuna_id' => $vacuna->id,
                    'numero_lote' => $faker->bothify('LOT-####'),
                    'fecha_vencimiento' => Carbon::now()->addDays(rand(100, 1000))->format('Y-m-d'),
                    'stock_inicial' => 100,
                    'stock_actual' => 100,
                ]);
            }

            // =========================
            // 📋 RECORDATORIOS
            // =========================
            for ($i = 0; $i < 10; $i++) {
                Recordatorio::create([
                    'clinica_id' => $clinicaId,
                    'mascota_id' => $mascotas[array_rand($mascotas)]->id,
                    'tipo' => $faker->randomElement(['Vacuna', 'Cita', 'Control']),
                    'mensaje' => $faker->sentence(),
                    'fecha_programada' => $faker->date(),
                    'estado' => 'pendiente',
                ]);
            }

            // =========================
            // 🏥 HISTORIAL MEDICO
            // =========================
            for ($i = 0; $i < 10; $i++) {
                HistorialMedico::create([
                    'clinica_id' => $clinicaId,
                    'mascota_id' => $mascotas[array_rand($mascotas)]->id,
                    'veterinario_id' => $users[array_rand($users)]->id,
                    'motivo_consulta' => $faker->sentence(),
                    'diagnostico' => $faker->paragraph(),
                    'tratamiento' => $faker->paragraph(),
                    'observaciones' => $faker->sentence(),
                    'fecha' => $faker->date(),
                ]);
            }

            // =========================
            // 💉 VACUNAS APLICADAS
            // =========================
            for ($i = 0; $i < 10; $i++) {
                VacunaAplicada::create([
                    'clinica_id' => $clinicaId,
                    'mascota_id' => $mascotas[array_rand($mascotas)]->id,
                    'vacuna_id' => $vacunas[array_rand($vacunas)]->id,
                    'lote_id' => $lotes[array_rand($lotes)]->id,
                    'veterinario_id' => $users[array_rand($users)]->id,
                    'fecha_aplicacion' => $faker->date(),
                    'observaciones' => $faker->sentence(),
                ]);
            }
        }
    }
}
