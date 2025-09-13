<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TwoFactorCodeMail extends Notification
{
    use Queueable;

    public function __construct(public string $code, public int $minutes = 10) {}

    public function via($notifiable): array { return ['mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu código de verificación')
            ->greeting('Hola '.$notifiable->name)
            ->line('Tu código de verificación es:')
            ->line('**'.$this->code.'**')
            ->line('Vence en '.$this->minutes.' minutos.')
            ->line('Si no solicitaste este código, ignora este correo.')
            ->salutation('');
    }
}
