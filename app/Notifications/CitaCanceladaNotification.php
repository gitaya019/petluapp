<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CitaCanceladaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $cita
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)

            ->subject('Cita cancelada')

            ->greeting('Hola ' . $notifiable->name)

            ->line(
                'La cita para tu mascota '
                . $this->cita->mascota->nombre
                . ' fue cancelada.'
            )

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

            ->line('Si necesitas reagendar la cita comunícate con la clínica.')

            ->salutation('PetluApp');
    }
}