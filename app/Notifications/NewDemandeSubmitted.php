<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDemandeSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $demande;
    protected $message;
    /**
     * Create a new notification instance.
     *
     * @param  $demande
     * @return void
     */
    public function __construct($demande)
    {
        $this->demande = $demande;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Determine if the demande is a relance.
     *
     * @return bool
     */
    protected function isRelance()
    {
        // Check if the demande has been updated after creation (i.e., it's a relance)
        return $this->demande->created_at != $this->demande->updated_at;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mess1 = $this->isRelance() ? "Demande Relancée" : "Nouvelle Demande Soumise";
        $this->message = $mess1;
        $mess2 = $this->isRelance() ? "Une demande a été relancée par le client." : "Une nouvelle demande a été soumise.";
        $mailMessage = new MailMessage();
        $mailMessage->subject($mess1)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($mess2)
            ->action('Voir la demande', url('/demandes/' . $this->demande->id))
            ->line('Veuillez vérifier les détails de la demande relancée.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'demande_id' => $this->demande->id,
            'client_id' => $this->demande->client_id,
            'montant' => $this->demande->montant_total,
            'is_relance' => $this->isRelance(),
            'message'=>$this->message
        ];
    }
}
