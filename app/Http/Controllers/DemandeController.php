<?php

namespace App\Http\Controllers;

use App\Enums\CategoryEnum;
use App\Services\DemandeServiceInterface;
use Auth;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    protected $demandeService;

    public function __construct(DemandeServiceInterface $demandeService)
    {
        $this->demandeService = $demandeService;
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('demande');
            $data = $request->all();
            return $this->demandeService->handleCreateDemande($data);
        } catch (AuthorizationException $e) {
            if (Auth::user()->client) {
                $message = auth()->user()->client->category_label == CategoryEnum::Bronze->value
                ? "vous avez une ou des dettes non soldes vous ne pouvez pas faire de demande desole"
                : "Vous avez atteint le montant max de cummul de dette. desole";
            } else {
                $message = "seul les client peuvent faire une demande";
            }

            return [
                "statut" => "echec",
                "message" => "$message",
                "code" => 403,
            ];
        }
    }
    public function index()
    {
        Gate::authorize("onlyclient");
        return $this->demandeService->getDemandes();
    }
    public function relance($id)
    {
        Gate::authorize("onlyclient");
        // dd('ss');
        return $this->demandeService->relance($id);
    }

    public function notifDemande()
    {
        Gate::authorize("onlyclient");
        return $this->demandeService->notifDemande();
    }
    public function getBoutiquierNotifications()
    {
        return $this->demandeService->getBoutiquierNotifications();
    }
    public function getAllDemandesForBoutiquier()
    {
        return $this->demandeService->getAllDemandesForBoutiquier();
    }

// private function sendRelanceNotification($demande)
// {
//     // Assuming you have a notification system in place
//     // Send the notification to the boutiquier
//     $boutiquier = $demande->boutiquier; // Adjust according to your relationship
//     Notification::send($boutiquier, new RelanceNotification($demande));
// }

}
