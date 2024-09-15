<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RelanceNotification extends Notification
{
    private $demande;

    public function __construct($demande)
    {
        $this->demande = $demande;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Customize based on how you notify (e.g., mail, database, etc.)
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('La demande ' . $this->demande->id . ' a été relancée par le client.')
                    ->action('Voir la demande', url('/demandes/' . $this->demande->id))
                    ->line('Merci de traiter cette demande.');
    }

    public function toArray($notifiable)
    {
        return [
            'demande_id' => $this->demande->id,
            'message' => 'La demande a été relancée',
        ];
    }
}
