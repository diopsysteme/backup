<?php
namespace App\Services;

use App\Models\Dette;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use App\Repository\MongoRepository;
use App\Repository\FirebaseRepository;


class ArchiveService2
{
    protected $firebaseRepo;
    protected $mongoRepo;

    public function __construct(FirebaseRepository $firebaseRepo, MongoRepository $mongoRepo)
    {
        $this->firebaseRepo = $firebaseRepo;
        $this->mongoRepo = $mongoRepo;
    }

    public function getArchivedDebts($filters = [])
    {
        $firebaseData = $this->firebaseRepo->retrieveAll($filters);
        $mongoData = $this->mongoRepo->retrieveAll($filters);
        if (!$mongoData || !$firebaseData) {
            return !$mongoData ? $firebaseData : $mongoData;
        }
        $alldebts= (array_merge($firebaseData, $mongoData));
        return $alldebts;
        // dd($alldebts);
        // foreach ($alldebts as $debt){
        //     // dd($debt);
        //     $this->restoreData($debt);
        // }
    }
    public function getArchivedDebtsDate($filters = [],$path)
    {
        $firebaseData = $this->firebaseRepo->retrieve($filters,$path);
        $mongoData = $this->mongoRepo->retrieve($filters,$path);
        if (!$mongoData || !$firebaseData) {
            return !$mongoData ? $firebaseData : $mongoData;
        }
        // dd($firebaseData);
        // $this->restoreData(array_merge($firebaseData, $mongoData));
        return (array_merge($firebaseData, $mongoData));
    }

    public function restoreDebtById($detteId)
    {
        $debtFire = $this->firebaseRepo->restore($detteId);
        $debtMongo = $this->mongoRepo->restore($detteId);

        $debts = array_merge($debtFire, $debtMongo);
        if (!empty($debts)) {
            foreach ($debts as $debt) {
                $this->restoreData($debt);
            }
        }
        return ["message"=>"debt restored successfully"];
    }
    public function restoreDebtsByDate($date)
    {
        $firebaseDebts = $this->firebaseRepo->restoreByDate($date);
        $mongoDebts = $this->mongoRepo->restoreByDate($date);
        if(!$mongoDebts||!$firebaseDebts){
            $allDebts = !$mongoDebts?$firebaseDebts:$mongoDebts;
        }else{
            $allDebts = array_merge($firebaseDebts, $mongoDebts);
        }
        $this->restoreData($allDebts);
        return ["message"=>"debts restored successfully FOR $date"];
        // dd($allDebts);
        // if (!empty($allDebts)) {
        //     foreach ($allDebts as $debt) {
        //         $this->restoreData($debt);
        //     }
        // }
    }

    public function restoreByClient($client_id)
    {
        $firebaseDebts = $this->mongoRepo->restoreByClientId($client_id);
        $mongoDebts = $this->firebaseRepo->restoreByClientId($client_id);

        $allDebts = array_merge($firebaseDebts, $mongoDebts);
        // return($allDebts);
        if (!empty($allDebts)) {
            foreach ($allDebts as $debt) {
                $this->restoreData($debt);
            }
        }
        return ["message"=>"debts restored successfully"];
        // dd($allDebts);
    }
    public function deleteDebtById($id)
    {
        $this->mongoRepo->deleteByIdDebt($id);
        $this->firebaseRepo->deteleByDebtId($id);
    }

    public function restoreData($data)
    {
        // Start database transaction to ensure atomic operations
        DB::beginTransaction();
    
        try {
            // dd($data);
            foreach ($data as $clientData) {
                // Check if client exists, otherwise create a new client
                // $client = Client::updateOrCreate(
                //     ['client_id' => $clientData['client_id']], // Match on client_id
                //     [
                //         'nom' => $clientData['nom'],
                //         'telephone' => $clientData['telephone'],
                //         'updated_at' => $clientData['updated_at'],
                //         'created_at' => $clientData['created_at']
                //     ]
                // );
                // Loop through each debt of the client
                foreach ($clientData['dettes'] as $detteData) {
                    // dd($detteData);
                    // dd($detteData);
                    // Create or update the debt record
                    $dette = Dette::create([
                        'client_id' => $clientData['client_id'], // Relating the debt to the client
                        'montant' => $detteData['montant_dette'],
                        'date' => now(),
                        'user_id' => auth()->id(), // Assuming the authenticated user
                        // 'updated_at' => now(),
                        // 'created_at' => now(),
                        // 'updated_at' => now(),
                    ]);
                    // Handle the payments
                    foreach ($detteData['payments'] as $paymentData) {
                        $dette->payement()->create([
                            // 'payment_id' => $paymentData['payment_id'],
                            'montant' => $paymentData['montant'],
                            'date_paiement' => $paymentData['date'],
                            'user_id' => auth()->id(), // Assuming the authenticated user
                        ]);
                    }
                    
                    // Handle the articles related to the debt
                    foreach ($detteData['articles'] as $articleData) {
                        // Attach the articles to the debt without adjusting stock
                        $dette->articles()->attach($articleData['article_id'], [
                            'qte_vente' => $articleData['quantite'],
                            'prix_vente' => $articleData['prix_vente']
                        ]);
                    }
                }
            }
            //  dd($dette);
    
            // Commit the transaction
            DB::commit();
            return ['status' => 'success', 'message' => 'Data restored successfully'];
    
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    

}
