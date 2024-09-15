<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NewDemandeSubmitted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyBoutiquiersOfNewDemande implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $demande;

    /**
     * Create a new job instance.
     *
     * @param  $demande
     * @return void
     */
    public function __construct($demande)
    {
        $this->demande = $demande;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::all();

// Filtrer les utilisateurs avec le rôle "Boutiquier"
$boutiquiers = $users->filter(function ($user) {
    return $user->hasRole('Boutiquier');
});
        // Envoi de la notification à chaque boutiquier
        foreach ($boutiquiers as $boutiquier) {
            $boutiquier->notify(new NewDemandeSubmitted($this->demande));
        }
    }
}
