<?php

namespace App\Jobs;

use App\Models\Dette;
use Illuminate\Bus\Queueable;
use App\Facades\ArchiveFacade;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ArchiveDetteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $dettes = Dette::all()
            ->load(['client', 'payement', 'articles'])
            ;

        $clients = $dettes->filter(function ($dette) {
            return $dette->etat_solde;
        })->groupBy('client_id') 
        ->map(function ($dettes) {
            $client = $dettes->first()->client;

            return [
                'id' => $client->id,
                'nom' =>$client->user? $client->user->prenom." ".$client->user->nom:$client->surnom,
                'telephone' => $client->telephone,
                'dettes' => $dettes->map(function ($dette) {
                    return [
                        'dette_id' => $dette->id,
                        'montant_dette' => $dette->montant,
                        'payments' => $dette->payement->map(function ($payment) {
                            return [
                                'payment_id' => $payment->id,
                                'montant' => $payment->montant,
                                'date' => $payment->created_at->toDateString(),
                            ];
                        })->toArray(),
                        'articles' => $dette->articles->map(function ($article) {
                            return [
                                'article_id' => $article->id,
                                'libelle' => $article->libelle,
                                'prix_vente' => $article->pivot->prix_vente,
                                'quantite' => $article->pivot->qte_vente,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        })->toArray();
        ArchiveFacade::archiveSoldedDettes($clients);
        
        // dd("ss");
        $dettes->filter(function ($dette) {
            return $dette->etat_solde;
        })->each(function ($dette) {
            $dette->delete();
        });
    }
}
