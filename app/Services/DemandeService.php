<?php

// app/Services/DemandeService.php

namespace App\Services;

use App\Models\Article;
use App\Models\Demande;
use App\Enums\EtatDetteEnum;
use Gate;
use Illuminate\Support\Facades\Auth;
use App\Jobs\NotifyBoutiquiersOfNewDemande;
use App\Repository\DemandeRepositoryInterface;

class DemandeService implements DemandeServiceInterface
{
    protected $demandeRepository;

    public function __construct(DemandeRepositoryInterface $demandeRepository)
    {
        $this->demandeRepository = $demandeRepository;
    }
    public function handleCreateDemande(array $data)
    {
        $clientId = auth()->user()->client->id;
        // $clientId = 182;
        $totalMontant = 0;
        $validatedArticles = [];
        $invalidArticles = [];

        foreach ($data['articles'] as $articleData) {
            $article = Article::find($articleData['id']);

            if (!$article || $articleData['qte_vente'] <= 0) {
                $invalidArticles[] = [
                    'article_id' => $articleData['id'],
                    'message' => !$article ? 'Article non trouvé' : 'Quantité invalide',
                ];
                continue;
            } else {
                if ($article->stock >= $articleData['qte_vente']) {
                    $validatedArticles[] = [
                        'article_id' => $article->id,
                        'quantite' => $articleData['qte_vente'],
                        'montant' => 1,
                    ];
                    
                } else {
                    $validatedArticles[] = [
                        'article_id' => $article->id,
                        'quantite' => $articleData['qte_vente'],
                        'montant' => 0,
                    ];
                }
            }
            $totalMontant += $article->prix * $articleData['qte_vente'];
        }
        // dd($totalMontant);
        $demande = $this->demandeRepository->createDemandeWithArticles($clientId, $totalMontant, $validatedArticles);

        return [
            'demande' => $demande,
            'invalid_articles' => $invalidArticles,
        ];
    }
    public function createDemande(array $data)
    {
        return $this->demandeRepository->create($data);
    }

    public function updateDemande($id, array $data)
    {
        return $this->demandeRepository->update($id, $data);
    }
    public function getDemandeById($id)
    {
        return $this->demandeRepository->find($id);
    }
    public function getDemandes()
    {
        return $this->demandeRepository->getDemandes();
    }
    public function relance($id){
        $demande = Demande::find($id);
        Gate::authorize("ableToAccess", $demande);
        if (!$demande || $demande->etat !== EtatDetteEnum::annule->value) {
            return ['message' => 'La demande n\'est pas annulée ou n\'existe pas'];
        }
        $updatedAt = $demande->updated_at;
        if (now()->diffInDays($updatedAt) > 2) {
            return ['message' => 'La relance est impossible, délai de 2 jours dépassé'];
        }
        $demande->etat = EtatDetteEnum::en_cours->value;
        $demande->save();
        NotifyBoutiquiersOfNewDemande::dispatch($demande);
        return ['message' => 'La demande a été relancée avec succès'];
    }
    public function notifDemande(): array{
        $client = auth()->user()->client;

        if (!$client) {
            return ['message' => 'Client not found'];
        }
    
        $notifications = $client->user->notifications()->where('type', 'App\Notifications\NewDemandeSubmitted')->get();
    
        return [
            'notifications' => $notifications
        ];
    }
public function getBoutiquierNotifications(){
Gate::authorize("client");

    $notifications = auth()->user()->notifications()->where('type', 'App\Notifications\NewDemandeSubmitted')->get();

    return[
        'notifications' => $notifications
    ];
}
public function getAllDemandesForBoutiquier(){
    // dd("dd");
    Gate::authorize("client");
    // dd("dd");
    return $this->getAllDemandesForBoutiquier();
}
}
