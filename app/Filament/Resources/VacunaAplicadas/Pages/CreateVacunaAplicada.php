<?php

namespace App\Filament\Resources\VacunaAplicadas\Pages;

use App\Filament\Resources\VacunaAplicadas\VacunaAplicadaResource;
use App\Models\Recordatorio;
use App\Notifications\RecordatorioVacunaNotification;
use App\Notifications\VacunaAplicadaNotification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateVacunaAplicada extends CreateRecord
{
    protected static string $resource = VacunaAplicadaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $vacunaAplicada = $this->record;

        $vacuna = $vacunaAplicada->vacuna;

        // =========================
        // 📅 SOLO RECORDATORIOS
        // =========================
        $diasRefuerzo = $vacuna?->dias_refuerzo;

        if (!$diasRefuerzo || $diasRefuerzo <= 0) {

            Notification::make()
                ->title('Vacuna aplicada')
                ->success()
                ->body('Vacuna registrada sin refuerzos programados')
                ->send();

            return;
        }

        $proxima = $vacunaAplicada->fecha_aplicacion->copy()->addDays($diasRefuerzo);

        $fechas = [
            ['tipo' => 'refuerzo_15_dias', 'fecha' => $proxima->copy()->subDays(15)],
            ['tipo' => 'refuerzo_1_dia', 'fecha' => $proxima->copy()->subDay()],
        ];

        foreach ($fechas as $r) {

            if ($r['fecha']->isPast()) continue;

            Recordatorio::create([
                'clinica_id' => $vacunaAplicada->clinica_id,
                'mascota_id' => $vacunaAplicada->mascota_id,
                'vacuna_id' => $vacunaAplicada->vacuna_id,
                'vacuna_aplicada_id' => $vacunaAplicada->id,
                'tipo' => $r['tipo'],
                'mensaje' => 'Recordatorio de vacuna',
                'fecha_programada' => $r['fecha'],
                'estado' => 'pendiente',
                'enviado' => false,
            ]);
        }

        Notification::make()
            ->title('Vacuna aplicada')
            ->success()
            ->body('Recordatorios programados correctamente')
            ->send();
    }
}
