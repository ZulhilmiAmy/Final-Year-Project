<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Tetapan Semula Kata Laluan')
            ->greeting('Hai!')
            ->line('Anda menerima emel ini kerana kami menerima permintaan untuk menetapkan semula kata laluan akaun anda.')
            ->action('Tetapkan Semula Kata Laluan', $url)
            ->line('Pautan ini hanya sah selama 60 minit.')
            ->line('Jika anda tidak meminta penetapan semula kata laluan, abaikan sahaja emel ini.')
            ->salutation('Terima kasih, ' . config('app.name'));
    }
}
