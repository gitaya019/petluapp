<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Recordatorio;
use App\Notifications\RecordatorioVacunaNotification;
use Illuminate\Support\Facades\DB;


#[Signature('app:enviar-recordatorios-vacunas')]
#[Description('Command description')]
class EnviarRecordatoriosVacunas extends Command
{
    /**
     * Execute the console command.
     */

    public function handle()
    {
        DB::transaction(function () {

            $recordatorios = Recordatorio::where('enviado', false)
                ->whereDate('fecha_programada', today())
                ->get();

            foreach ($recordatorios as $recordatorio) {

                $dueno = $recordatorio->mascota?->user;

                if (!$dueno?->email) continue;

                $dueno->notify(
                    new RecordatorioVacunaNotification($recordatorio)
                );

                $recordatorio->update([
                    'estado' => 'enviado',
                    'enviado' => true,
                    'enviado_at' => now(),
                    'correo_destino' => $dueno->email,
                ]);
            }
        });
    }
}
