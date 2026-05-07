<?php

namespace App\Filament\Resources\Recordatorios\Pages;

use App\Filament\Resources\Recordatorios\RecordatorioResource;
use App\Notifications\RecordatorioVacunaNotification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateRecordatorio extends CreateRecord
{
    protected static string $resource = RecordatorioResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $recordatorio = $this->record;

        $dueno = $recordatorio->mascota?->user;

        // =========================
        // 📧 ENVÍO INMEDIATO (MANUAL)
        // =========================
        if ($dueno?->email) {

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

        // =========================
        // 🧠 TOAST
        // =========================
        Notification::make()
            ->title('Recordatorio enviado')
            ->success()
            ->body('El correo fue enviado inmediatamente')
            ->send();
    }
}