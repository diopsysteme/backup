<?php
namespace App\Services;

use App\Models\Dette;
use App\Models\Article;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\DB;
use App\Repository\DetteRepository;
use App\Repository\DetteRepositoryInterface;

class FirebaseArchiveService implements ArchiveServiceInterface
{
    protected $database;

    public function __construct()
    {

        
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.key_file'))
            ->withDatabaseUri(config('firebase.database.url'));
            
        $this->database = $factory->createDatabase();
    }

    public function archiveSoldedDettes(array $clients): void
    {
        $today = now()->format('Y_m_d');
        $reference = $this->database->getReference('dettes_' . $today);

        foreach ($clients as $client) {
            $reference->push([
                'client_id' => $client['id'],
                'nom' => $client['nom'],
                'telephone' => $client['telephone'],
                'dettes' => $client['dettes'],
            ]);
        }
    }
    public function getArchivedDettes(array $filters = []): array
{
    $referencePath = isset($filters['date']) ? 'dettes_' . $filters['date'] : null;

    try {
        $debts = [];

        if ($referencePath) {
            $debtsSnapshot = $this->database->getReference($referencePath)->getSnapshot();

            if ($debtsSnapshot->exists()) {
                $debts = $debtsSnapshot->getValue();
            }
        } else {
            $globalSnapshot = $this->database->getReference()->getSnapshot();

            if ($globalSnapshot->exists()) {
                $globalData = $globalSnapshot->getValue();
                $debts = $this->extractDebtsFromGlobalData($globalData, $filters);
                
            }
        }

        return $this->filterDettes($debts, $filters);
    } catch (\Exception $e) {
        return ['error' => 'Failed to retrieve data: ' . $e->getMessage()];
    }
}

private function extractDebtsFromGlobalData(array $globalData, array $filters): array
{
    $debts = [];

    foreach ($globalData as $key => $entry) {
        foreach ($entry as $ent) {
            if (is_array($ent)) {
                $client_id = isset($ent['client_id']) ? $ent['client_id'] : null;

                if (isset($filters['client_id']) && $client_id != $filters['client_id']) {
                    continue; 
                }
                if (isset($ent['dettes']) && is_array($ent['dettes'])) {
                    foreach ($ent['dettes'] as $dette) {
                        if (is_array($dette)) {
                            $debts[] = array_merge($ent, ['dette' => $dette]);
                        } else {
                            dd('Invalid Debt Entry:', $dette);
                        }
                    }
                } else {
                    dd('No Debts Found:', $ent);
                }
            } else {
                dd('Invalid Entry Format:', $ent);
            }
        }
    }


    return $debts;
}






// Helper function to apply filtering to the fetched data
private function filterDettes(array $debts, array $filters): array
{
    $filteredResults = [];

    foreach ($debts as $debt) {
        // Apply client_id filter if specified
        if (isset($filters['client_id']) && $debt['client_id'] != $filters['client_id']) {
            continue;
        }

        // Apply dette_id filter within the dettes array, if specified
        if (isset($filters['dette_id'])) {
            $debt['dettes'] = array_filter($debt['dettes'], function ($dette) use ($filters) {
                return $dette['dette_id'] == $filters['dette_id'];
            });

            // Skip if no matching debts are found after filtering by dette_id
            if (empty($debt['dettes'])) {
                continue;
            }
        }

        // Add the filtered debt to the results
        $filteredResults[] = $debt;
    }

    return $filteredResults;
}

public function getArchevedById($id){
    $debts=$this->getArchivedDettes();
    foreach($debts as $debt){
        // return $debt["dettes"];
         if($debt["dette"]["dette_id"]==$id){
            return $debt;
         }
    }
    return [];
}
public function restoreDetteById($id)
{
    // Fetch the archived debt from Firebase
    $dette = $this->getArchevedById($id);
    // dd($dette);
    // Prepare the format array
   
    $format = [
        "client_id" => $dette['client_id'],
        "articles" =>$dette['dette']['articles']    ,
        "payements"=>$dette['dette']['payments']
    ];

    // $format = json_decode(json_encode($format));
    // $format->client_id = $dette['client_id'];
    // $format->articles = $dette['dette']['articles'];
// dd($format);
    // Map the articles to the desired format

    $dette=$this->verifierEtEnregistrerDette($format);
    return $dette;
}
    // public function getArchivedDettes(array $filters=[]): array
    // {
    //     // Format the date filter correctly
    //     // $formattedDate = ;
    //     $referencePath = isset($filters['date'])?'dettes_' . $filters['date']:null;
    // if($referencePath){

    //     try {
    //         // Fetch the data from the specified date path
    //         $debtsSnapshot = $this->database->getReference($referencePath)->getSnapshot();
    
    //         if (!$debtsSnapshot->exists()) {
    //             return []; // Return empty if no data exists for the given date
    //         }
    
    //         $debts = $debtsSnapshot->getValue();

    
    //         // Filter results based on additional criteria, if provided
    //         return $this->filterDettes($debts, $filters);
    //     } catch (\Exception $e) {
    //         // Handle errors, such as invalid references or connection issues
    //         return ['error' => 'Failed to retrieve data: ' . $e->getMessage()];
    //     }
    // }
    // return  $this->filterDettes($this->database->getReference()->getSnapshot()->getValue(), $filters);
    // }
    
    // // Helper function to apply filtering to the fetched data
    // private function filterDettes(array $debts, array $filters): array
    // {
    //     $filteredResults = [];
    
    //     foreach ($debts as $key => $debt) {
    //         dd($debt);
    //         // Apply client_id filter if specified
    //         if (isset($filters['client_id']) && $debt['client_id'] != $filters['client_id']) {
    //             continue;
    //         }
    
    //         // Apply dette_id filter within the dettes array
    //         if (isset($filters['dette_id'])) {
    //             $debt['dettes'] = array_filter($debt['dettes'], function ($dette) use ($filters) {
    //                 return $dette['dette_id'] == $filters['dette_id'];
    //             });
    
    //             // Skip if no matching debts are found
    //             if (empty($debt['dettes'])) {
    //                 continue;
    //             }
    //         }
    
    //         $filteredResults[] = $debt;
    //     }
    
    //     return $filteredResults;
    // }
    

    public function getArchivedDettes2(array $filters = null): array
{
    $reference = $this->database->getReference();
    
    if (isset($filters['date'])) {
        // Construct the reference path for the specific date
        $reference2 = $reference->getChild('dettes_' . $filters['date']);
        $query = $reference2->getSnapshot()->getValue();
        
        // Check if the result is not empty and return it
        if ($query) {
            return $query;
        } else {
            return [];
        }
    }
    
    // If no date filter is applied, proceed with fetching all data
    $query = $reference->getChildKeys();
    
    if (!$query) {
        return [];
    }
    
    $filteredResults = [];
    
    foreach ($query as $key) {
        $detteReference = $reference->getChild($key);
        $detteValue = $detteReference->getValue();
        
        foreach ($detteValue as $subKey => $subValue) {
            foreach ($subValue as $innerKey => $innerValue) {
                // Check if client_id filter is set and matches the current record
                if (isset($filters['client_id'])) {
                    if (isset($innerValue['client_id']) && $innerValue['client_id'] == $filters['client_id']) {
                        $filteredResults[$innerKey] = $innerValue;
                    }
                } else {
                    $filteredResults[$innerKey] = $innerValue;
                }
            }
        }
    }
    
    return $filteredResults;
}

    



    public function restoreDettesByClientId(string $clientId): void
    {
        $dettes = $this->getArchivedDettes(['client_id' => $clientId]);

        foreach ($dettes as $id => $dette) {
            $this->restoreDetteById($id);
        }
    }

    public function restoreDettesByDate(string $date): void
    {
        $reference = $this->database->getReference('dettes_' . $date);
        $dettes = $reference->getValue() ?? [];

        foreach ($dettes as $id => $dette) {
            $reference->getChild($id)->remove();
            // Add code here to reinsert into the main database if needed
        }
    }

    public function displayArchivedDetteWithDetails(string $id): array
    {
        $reference = $this->database->getReference('dettes')->getChild($id);
        return $reference->getValue() ?? [];
    }
    public function verifierEtEnregistrerDette(array $data)
{
    $articlesValides = [];
    $articlesInvalides = [];
    $montant_total = 0;

    foreach ($data['articles'] as $articleData) {
        $article = Article::find($articleData['article_id']);

        if (!$article) {
            $articlesInvalides[] = [
                'article_id' => $articleData['article_id'],
                'erreur' => 'Article non trouvé.'
            ];
            continue;
        }

        if ($articleData['quantite'] <= 0) {
            $articlesInvalides[] = [
                'article_id' => $articleData['article_id'],
                'erreur' => 'Quantité invalide.'
            ];
            continue;
        }

        if ($article->qtstock < $articleData['quantite']) {
            $articlesInvalides[] = [
                'article_id' => $articleData['article_id'],
                'erreur' => 'Stock insuffisant.'
            ];
            continue;
        }

        $articlesValides[] = $articleData;
        $montant_total += $articleData['quantite'] * $articleData['prix_vente'];
        // dd($montant_total);
    }

    if (empty($articlesValides)) {
        return [
            'statut' => 'echec',
            'message' => 'Aucun article valide.',
            'articlesInvalides' => $articlesInvalides
        ];
    }

    // if ($data['montant_verse'] > $montant_total) {
    //     return [
    //         'statut' => 'echec',
    //         'message' => 'Le montant versé ne peut pas être supérieur ou égal au montant total.',
    //     ];
    // }

    $all['articles_invalides'] = $articlesInvalides;
    $all['articles_valides'] = $articlesValides;
    $data['articlesValides'] = $articlesValides;

    // Start a transaction to ensure atomic operations
    DB::beginTransaction();

    try {
        // Save the valid data using Dette model
        $dette = new Dette();
        $dette->client_id = $data['client_id'];
        $dette->date = now();
        $dette->montant = $montant_total;
        $dette->user_id = auth()->id();
        $dette->save();

        // Attach articles to the debt using the pivot table
        foreach ($articlesValides as $article) {
            $dette->articles()->attach($article['article_id'], [
                'qte_vente' => $article['quantite'],
                'prix_vente' => $article['prix_vente']
            ]);

            // Decrement the stock quantity
            $articleModel = Article::find($article['article_id']);
            $articleModel->qtstock -= $article['quantite'];
            $articleModel->save();
        }

        // Save payments if any
        foreach ($data['payements'] as $payment) {
            $dette->payement()->create([
                'payment_id' => $payment['payment_id'],
                'date_paiement' => $payment['date'],
                'montant' => $payment['montant'],
                'user_id'=> auth()->id()
            ]);
        }

        // Commit the transaction
        DB::commit();

        return ["statut" => "succès", "result" => $dette, "article" => $all];

    } catch (\Exception $e) {
        // Rollback the transaction if any error occurs
        DB::rollBack();
        return [
            'statut' => 'echec',
            'message' => 'Une erreur est survenue.',
            'erreur' => $e->getMessage()
        ];
    }
}
    

}
