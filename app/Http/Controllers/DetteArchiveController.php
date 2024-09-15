<?php
namespace App\Http\Controllers;
use App\Models\Dette;
use App\Models\Client;
use App\Models\DetteArchive;
use App\Repository\FirebaseRepository;
use Illuminate\Http\Request;
use App\Services\SmsService2;
use App\Facades\ArchiveFacade;
use App\Services\ArchiveService2;
use App\Repository\MongoRepository;
use Illuminate\Support\Facades\Log;
use App\Facades\SmsServiceFacade as SmsService;

class DetteArchiveController extends Controller
{
    protected $taf;
    protected $fire;
    public function __construct(ArchiveService2 $archiveService2) {
        $this->taf = $archiveService2;
    }
    public function show()
    {
        //  return $this->taf->getArchivedDebtsDate([], 'dettes_2024_09_14');
        // return $this->taf->restoreDebtById(117);
        // return $this->taf->restoreByClient(204);
       return $this->taf->restoreDebtsByDate('dettes_2024_09_14');
    //     $this->taf->deleteDebtById(41);
        // $dettes = $this->taf->getArchivedDebts(['client_id' => 210]);
        // return $dettes;
        // $this->taf->deleteDebtById(2);
    //    $dettes=$this->taf->getArchivedDebts(['client_id' => 210]);
    //    return $dettes;

    //     $test=new ArchiveService2();
    //     return ArchiveFacade::restoreDetteById(41);
    //    return ArchiveFacade::getArchevedById(41);
    // return ArchiveFacade::getArchivedDettes();
        // return ArchiveFacade::getArchivedDettes(['date' => '2024_09_11', 'client_id' => 210]);
        // return ArchiveFacade::getArchivedDettes(['client_id' => 210]);
        // return ArchiveFacade::getArchivedDettes(['date' => '2024_09_12']);





        // $smsService = new SmsService2();
        // $smsService->sendMessage('+16193323265', '+221785342948', 'hey est ce que diall de vrai!');

    //     $clients = Client::with('dettes')->get();

    //     // Parcourir chaque client
    //     foreach ($clients as $client) {
    //         // Filtrer les dettes non payées
    //         $totalDettes = $client->dettes->filter(function ($dette) {
    //             return $dette->montant_restant > 0; // Utilise l'attribut calculé pour le montant restant
    //         })->sum('montant_restant');

    //         // Vérifie s'il y a des dettes à rappeler
    //         if ($totalDettes > 0) {
    //             // Message à envoyer
    //             $nom=$client->user?$client->user->prenom.' '.$client->user->nom: $client->surnom;
    //             $message = "Bonjour {$nom}, vous avez un total de {$totalDettes} FCFA de dettes chez DIOP E-SHOP.";
    //         Log::info($message);
    //         // Envoi du SMS
    //         // SmsService::sendMessage('DIOP E-SHOP', $client->phone_number, $message);
    //     }
    // }

// SmsService::sendMessage('DIOP E-SHOP', '+221785342948', 'Bonjour, voici votre message.');

    //     $dettes = Dette::all()
    //         ->load(['client', 'payement', 'articles'])
    //         ;

    //     $clients = $dettes->filter(function ($dette) {
    //         return $dette->etat_solde;
    //     })->groupBy('client_id') 
    //     ->map(function ($dettes) {
    //         $client = $dettes->first()->client;

    //         return [
    //             'id' => $client->id,
    //             'nom' =>$client->user? $client->user->prenom." ".$client->user->nom:$client->surnom,
    //             'telephone' => $client->telephone,
    //             'dettes' => $dettes->map(function ($dette) {
    //                 return [
    //                     'dette_id' => $dette->id,
    //                     'montant_dette' => $dette->montant,
    //                     'payments' => $dette->payement->map(function ($payment) {
    //                         return [
    //                             'payment_id' => $payment->id,
    //                             'montant' => $payment->montant,
    //                             'date' => $payment->created_at->toDateString(),
    //                         ];
    //                     })->toArray(),
    //                     'articles' => $dette->articles->map(function ($article) {
    //                         return [
    //                             'article_id' => $article->id,
    //                             'libelle' => $article->libelle,
    //                             'prix_vente' => $article->pivot->prix_vente,
    //                             'quantite' => $article->pivot->qte_vente,
    //                         ];
    //                     })->toArray(),
    //                 ];
    //             })->toArray(),
    //         ];
    //     })->values()->toArray();
    //     ArchiveFacade::archiveSoldedDettes($clients);
    // DetteArchive::create([
    //     'field1' => 'value1',
    //     'field2' => 'value2',
    // ]);
    //    return  [

    //        'dette' => DetteArchive::all(),
    //    ];
   }

}