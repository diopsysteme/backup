<?php

namespace App\Http\Controllers;
use App\Exceptions\ServiceException;
use App\Http\Resources\DetteResource;
use DB;
use App\Models\Role;
use App\Models\User;
use App\Models\Dette;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Enums\StatutEnum;
use App\Services\QrService;
use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\ClientResource;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\GetClientByPhoneRequest;

class ClientController extends Controller
{
    protected $clientService;
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    use ApiResponseTrait;
//     public function index(Request $request)
// {
//     try {
//          $clien = $this->clientService->getAllClient($request);

//     $clients = Client::filterByRequest($request)->paginate(10);

//     return $this->sendResponse(StatutEnum::SUCCESS, $clients, 'Clients retrieved successfully');
//     } catch (\Exception $e) {
//         return $this->sendResponse(
//             StatutEnum::ECHEC,
//             null,
//             'Erreur lors de la récupération de la liste des clients : ' . $e->getMessage()
//         );
//     }
// }
// public function dettes($clientId)
//     {
//         $client = Client::find($clientId);

//         if (!$client) {
//             return $this->sendResponse(
//                 StatutEnum::ECHEC,
//                 null,
//                 'Client non trouvé.'
//             );
//         }
//         return $this->sendResponse(
//             StatutEnum::SUCCESS,
//             $client->dettes,
//             'Dettes récupérées avec succès.'
//         );
//     }
// public function filterByTelephone(GetClientByPhoneRequest $request)
// {
//         $clients = Client::query()->first();
//         return $this->sendResponse(
//             StatutEnum::SUCCESS,
//            new  ClientResource($clients),
//             'Clients trouvés avec succès.'
//         );
// }


    // public function show($id, Request $request)
    // {
    //     try{
    //         $query = Client::query();
            
    //         $client = $query->findOrFail($id);
    //         Gate::authorize('update-client',$client);
            
    
    //             if ($request->query('include') === 'user') {
    //                 $client->load('user');
    //             }
    
    //             return $this->sendResponse(StatutEnum::SUCCESS, new ClientResource($client) , 'Client trouvé.');
            
    //     }catch (\Exception $e) {
    //         return $this->sendResponse(StatutEnum::ECHEC, null, 'Erreur lors de la récupération du client : '. $e->getMessage());
    //     }
    // }
    // public function store(StoreClientRequest $request)
    // {
    //     try {
    //         Gate::authorize('client');
    //         DB::beginTransaction();
    //         $client = Client::create($request->only(['surnom', 'telephone', 'address']));
    
    //         $userData = $request->input('user', []);
    //         if ($request->has('user')) {
    //             $role = Role::where('label', RoleEnum::CLIENT)->first(); 
    //             $userData = collect($userData)->except(['password_confirmation'])->toArray();
    //             $user = User::create($userData);
    //             $role->users()->save($user);
    //             $client->user()->associate($user);
    //         }
    
    //         if ($request->hasFile('photo')) {
    //             $photoPath = $request->file('photo')->store('photos', 'public'); 
    //             $client->photo = $photoPath;
    //         }
    
    //         $client->save();
    //         $clientre = Client::where('id',$client->id)->first(); 
    //         if ($clientre->user) {
    //             $clientre->load('user');
    //         }
            
    //         $clientResource = new ClientResource($clientre);
    //         DB::commit();
            
    //         return $this->sendResponse(
    //             StatutEnum::SUCCESS,
    //             $clientResource,
    //             'Client créé avec succès.'
    //         );
    
    //     } catch (\Exception $e) {
    //         DB::rollBack(); 
    //         return $this->sendResponse(
    //             StatutEnum::ECHEC,
    //             null,
    //             'Une erreur est survenue lors de la création du client. Veuillez réessayer.'.$e->getMessage()
    //         );
    //     }
    // }

    public function index(Request $request)
    {
        try {
            $clients = $this->clientService->getAllClient($request);

            return ClientResource::collection($clients);
             
        } catch (\Exception $e) {
            return [
                'statut' => 'KO',
                'message' => 'Erreur lors de la récupération de la liste des clients : ' . $e->getMessage(),
            ];
        }
    }

    public function store(StoreClientRequest $request)
    {
        try {
            $clientData = $request->only(['surnom', 'telephone', 'address','max_montant','category_id']);
            // $clientData['user_id'] = Auth::id();
            $userData = $request->input('user', []);
            $client = $this->clientService->createClient(
                $clientData,
                !empty($userData) ? collect($userData)->except(['password_confirmation'])->toArray() : null,
                $request->hasFile('user.photo') ? $request->file('user.photo') : null
            );
            return new ClientResource($client->load('user'));
        } catch (ServiceException $e) {
            return [
                'statut' => 'KO',
                'message' => 'Erreur lors de la création du client : ' . $e->getMessage(),
            ];
        }
    }

    public function dettes($clientId)
    {
        try {
            $client = $this->clientService->getClientWithDebts($clientId);
            Gate::authorize('update-client', $client);
            return DetteResource::collection($client->dettes);
        } catch (ServiceException $e) {
            return [
                'statut' => 'KO',
                'message' => 'Client non trouvé ou erreur lors de la récupération des dettes : ' . $e->getMessage(),
            ];
        }
    }

    public function filterByTelephone(GetClientByPhoneRequest $request)
    {
        try {
            $client = $this->clientService->findByTelephone($request->telephone);
            return new ClientResource($client);
        } catch (ServiceException $e) {
            return [
                'statut' => 'KO',
                'message' => 'Erreur lors de la récupération du client par téléphone : ' . $e->getMessage(),
            ];
        }
    }

    public function show($id, Request $request)
    {
        try {
            $client = $this->clientService->getClientWithDetails($id, $request->query('include'));
            Gate::authorize('update-client', $client);
            $lastSegment = request()->segment(count(request()->segments()));
            if ($lastSegment === 'user') {
                return new ClientResource($client->load("user"));
            }
            return new ClientResource($client);
        } catch (ServiceException $e) {
            return [
                'statut' => 'KO',
                'message' => 'Erreur lors de la récupération du client : ' . $e->getMessage(),
            ];
        }
    }

    public function getUserThroughClient($id)
    { 

        try {
            $user = $this->clientService->getUserByClient($id);

            return new UserResource($user->load('client'));  // Le middleware s'occupe du format de la réponse
        } catch (ServiceException $e) {
            return [
                'statut' => 'KO',
                'message' => 'Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage(),
            ];
        }
    }
    
}
