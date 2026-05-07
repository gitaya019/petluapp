<?php

namespace App\Notifications;

use App\Models\Recordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioVacunaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Recordatorio $recordatorio
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // 🔥 Cargar relaciones para evitar nulls
        $mascota = $this->recordatorio->mascota;
        $vacuna = $this->recordatorio->vacuna;

        return (new MailMessage)
            ->subject('Recordatorio de vacuna')
            ->greeting('Hola ' . ($notifiable->name ?? 'Usuario'))
            ->line('Tu mascota tiene una próxima vacuna.')
            ->line('Mascota: ' . ($mascota?->nombre ?? 'No registrada'))
            ->line('Vacuna: ' . ($vacuna?->nombre ?? 'No registrada'))
            ->line('Fecha programada: ' . optional($this->recordatorio->fecha_programada)->format('Y-m-d'))
            ->line('Por favor agenda una cita.')
            ->line('Gracias por confiar en nosotros.');
    }
}
