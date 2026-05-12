<?php

namespace App\Filament\Resources\Citas\Pages;

use App\Models\Recordatorio;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use App\Notifications\CitaConfirmadaNotification;
use App\Filament\Resources\Citas\CitaResource;

class CreateCita extends CreateRecord
{
    protected static string $resource = CitaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['clinica_id'] = Filament::getTenant()->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $cita = $this->record;

        $mascota = $cita->mascota;

        $cliente = $mascota->user;

        // =====================================================
        // SOLO SI LA CITA ESTÁ CONFIRMADA
        // =====================================================

        if (
            $cliente?->email &&
            $cita->estado === 'confirmada'
        ) {

            // =================================================
            // CREAR RECORDATORIO
            // =================================================

            Recordatorio::create([

                'clinica_id' => Filament::getTenant()->id,

                'mascota_id' => $mascota->id,

                'vacuna_id' => $cita->vacuna_id,

                'tipo' => 'cita',

                'mensaje' =>
                'Tienes una cita programada para '
                    . $mascota->nombre
                    . ' el día '
                    . $cita->fecha->format('d/m/Y')
                    . ' a las '
                    . \Carbon\Carbon::parse(
                        $cita->hora
                    )->format('h:i A'),

                'fecha_programada' => $cita->fecha,

                'estado' => 'enviado',

                'enviado' => true,

                'enviado_at' => now(),

                'correo_destino' => $cliente->email,
            ]);

            // =================================================
            // ENVIAR CORREO
            // =================================================

            $cliente->notify(
                new CitaConfirmadaNotification($cita)
            );
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
