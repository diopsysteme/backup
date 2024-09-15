<?php
namespace App\Services;

use App\Models\Article;
use App\Services\DetteServiceInterface;
use App\Repository\DetteRepositoryInterface;

class DetteService implements DetteServiceInterface
{
    protected $detteRepository;

    public function __construct(DetteRepositoryInterface $detteRepository)
    {
        $this->detteRepository = $detteRepository;
    }
    public function getAllDettes()
    {
        return $this->detteRepository->getAll();
    }
    public function verifierEtEnregistrerDette($request)
    {
        $articlesValides = [];
        $articlesInvalides = [];
        $montant_total = 0;

        foreach ($request->articles as $articleData) {
            $article = Article::find($articleData['id']);

            if (!$article) {
                $articlesInvalides[] = [
                    'id' => $articleData['id'],
                    'erreur' => 'Article non trouvé.'
                ];
                continue;
            }

            if ($articleData['qte_vente'] <= 0) {
                $articlesInvalides[] = [
                    'id' => $articleData['id'],
                    'erreur' => 'Quantité invalide.'
                ];
                continue;
            }

            if ($article->qtstock < $articleData['qte_vente']) {
                $articlesInvalides[] = [
                    'id' => $articleData['id'],
                    'erreur' => 'Stock insuffisant.'
                ];
                continue;
            }
            $articlesValides[] = $articleData;
            $montant_total += $articleData['qte_vente'] * $articleData['prix_vente'];
        }

        if (empty($articlesValides)) {
            return [
                'statut' => 'echec',
                'message' => 'Aucun article valide.',
                'articlesInvalides' => $articlesInvalides
            ];
        }
        if($request->montant_verse>$montant_total)
        {
            return [
                'statut' => 'echec',

                'message' => 'Le montant versé ne peut pas être supérieur ou égal au montant total.',
            ];
        }
        $all['articles_invalides']=$articlesInvalides;
        $all['articleds_valides']=$articlesValides;
        $request['articlesValides']=$articlesValides;
    
        return ["statut"=>"coull","result" =>$this->detteRepository->createDette($request, $montant_total), "article"=>$all];
    }

    public function enregistrerDette(array $data)
    {
        return $this->detteRepository->save($data);
    }
    public function getAllDettes2(){
        return $this->detteRepository->getAll2();
    }
    public function recupererDette($id, array $relations = [])
    {
        return $this->detteRepository->recupererDette($id, $relations);
    }
}
