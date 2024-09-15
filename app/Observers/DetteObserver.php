<?php

namespace App\Observers;

use App\Models\Dette;
use App\Models\Article;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DetteObserver
{
    /**
     * Handle the Dette "created" event.
     */
    public function created(Dette $dette): void
    {
        // dd($dette);
        $request = request();
        $montant_verse = $request->get('montant_verse', 0);
        // dd($montant_verse);
        $articlesValides = $request->get('articlesValides');
// dd($articlesValides);
        DB::beginTransaction();

        try {
            foreach ($articlesValides as $articleData) {
                $article = Article::find($articleData['id']);

                if ($article) {
                    $article->decrement('qtstock', $articleData['qte_vente']);
                    $dette->articles()->attach($article->id, [
                        'qte_vente' => $articleData['qte_vente'],
                        'prix_vente' => $articleData['prix_vente']
                    ]);
                }
            }
            if ($montant_verse > 0) {
                Payment::create([
                    'dette_id' => $dette->id,
                    'montant' => $montant_verse,
                    'date_paiement' => now(),
                    'moyen_paiement' => $request->get('moyen_paiement', 'espèces'), 
                    'user_id' => auth()->id(),
                ]);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Erreur lors de l'enregistrement de la dette ID {$dette->id} : " . $e->getMessage());
        }
    }

    /**
     * Handle the Dette "updated" event.
     */
    public function updated(Dette $dette): void
    {
        //
    }

    /**
     * Handle the Dette "deleted" event.
     */
    public function deleted(Dette $dette): void
    {
        //
    }

    /**
     * Handle the Dette "restored" event.
     */
    public function restored(Dette $dette): void
    {
        //
    }

    /**
     * Handle the Dette "force deleted" event.
     */
    public function forceDeleted(Dette $dette): void
    {
        //
    }
}
