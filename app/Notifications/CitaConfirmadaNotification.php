<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CitaConfirmadaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Cita $cita
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mascota = $this->cita->mascota;

        $veterinario = $this->cita->veterinario;

        return (new MailMessage)

            ->subject('Confirmación de cita')

            ->greeting('Hola ' . $notifiable->name)

            ->line(
                'Tu cita fue programada correctamente.'
            )

            ->line('Mascota: ' . $mascota->nombre)

            ->line(
                'Fecha: '
                . $this->cita->fecha->format('d/m/Y')
            )

            ->line(
                'Hora: '
                . \Carbon\Carbon::parse(
                    $this->cita->hora
                )->format('h:i A')
            )

            ->line(
                'Veterinario: '
                . ($veterinario?->name ?? 'Sin asignar')
            )

            ->line(
                'Estado: '
                . ucfirst($this->cita->estado)
            )

            ->line(
                'Gracias por confiar en nosotros.'
            );
    }
}