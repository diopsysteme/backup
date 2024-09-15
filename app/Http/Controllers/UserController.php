<?php

namespace App\Http\Controllers;
use App\Exceptions\ServiceException;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Enums\StatutEnum;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Services\UserServiceInterface;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{

    use ApiResponseTrait;
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    
    
    public function storeUserSaveClient(StoreUserRequest $request)
    {
        try {
            Gate::authorize('client');
            if (!$request->idclient){
                return ['statut' =>false,"message"=>'lid du client doit venir baye'];
            }
            $userData = $request->input('user', []);
            // dd($userData);

            $user = $this->userService->storeUserSaveClient($userData);
            if ($user["statut"]){
                return ['statut' =>false,"message"=>'Erreur lors de la création de l\'utilisateur ce client a deja un compte'];
            }
            return ["data"=> new UserResource($user), 'Utilisateur créé avec succès'];
        } catch (ServiceException $e) {
            return ["statut"=>"echec","message"=> 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage()];
        }
    }
 
    public function store(StoreUserRequest $request)
    {
        try {
            // dd('jjd');

            
            Gate::authorize('admin');
            if (!$request->idrole){
                return ['statut' =>false,"message"=>'le role doit venir baye'];
            }
            $userData = $request->input('user', []);
            $user = $this->userService->storeUserSaveClient($userData);
            // dd($request->idrole);
            return ["data"=> $user,"message"=> 'Utilisateur créé avec succès'];
        } catch (ServiceException $e) {
            return $this->sendResponse(StatutEnum::ECHEC, null, 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        try {
            Gate::authorize('admin');
            
            $users = $this->userService->getUsersWithFilters($request->all());
            return $this->sendResponse(
                StatutEnum::SUCCESS,
                UserResource::collection($users),
                'Liste des utilisateurs récupérée avec succès.'
            );
        } catch (Exception $e) {
            return $this->sendResponse(
                StatutEnum::ECHEC,
                null,
                'Erreur lors de la récupération de la liste des utilisateurs : ' . $e->getMessage()
            );
        }
    }




            public function storeUserSaveClient2(StoreUserRequest $request)
            {
                try {
                    Gate::authorize('client');
                    // Trouver le client par son ID au lieu de passer le query builder
                    DB::beginTransaction();
                    $client = Client::find($request->idclient);
                    if ($client->user()->exists()) {
                        throw new Exception("Le client a déjà un compte utilisateur associé.", 1);
                    }
                    
                    if (!$client) {
                        return $this->sendResponse(StatutEnum::ECHEC, null, 'Vous ne pouvez pas créer un compte user pour un client inexistant.');
                    }
            
                    // Créer l'utilisateur
                    $user = User::create($request->only(['login', 'password', 'nom', 'prenom']));
            
                    // Associer l'utilisateur au client
                    $user->client()->save($client);
            
                    // Associer le rôle CLIENT à l'utilisateur
                    $role = Role::where('label', RoleEnum::CLIENT->value)->first();
                    $role->users()->save($user);
        
                    DB::commit();
                    
            
                    return $this->sendResponse(StatutEnum::SUCCESS, new UserResource($user), 'Utilisateur créé avec succès');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->sendResponse(StatutEnum::ECHEC, null, 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
                }
            }
            
            public function update(Request $request, $id)
            {
                Gate::authorize('admin');
                $user = User::findOrFail($id);
        
                $validated = $request->validate([
                    'nom' => 'sometimes|string|max:255',
                    'prenom' => 'sometimes|string|max:255',
                    'login' => 'sometimes|string|unique:users,login,' . $id,
                    'password' => [
                        'sometimes',
                        'string',
                        'min:8',
                        'regex:/[A-Z]/',
                        'regex:/[a-z]/',
                        'regex:/[0-9]/',
                        'regex:/[@$!%*?&]/',
                    ],
                    'role' => 'sometimes|string|in:boutiquier,admin',
                ], [
                    'password.sometimes' => 'Le champ mot de passe est parfois requis.',
                    'password.string' => 'Le champ mot de passe doit être une chaîne de caractères.',
                    'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                    'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
                    'role.in' => 'Le rôle doit être soit "boutiquier" soit "admin".',
                ]);
        
        
                $user->update($validated);
        
                return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user]);
            }
            public function show($id)
        {
            try {
                Gate::authorize('admin');
                // Trouver l'utilisateur par son ID
                $user = User::findOrFail($id);
        
                // Retourner la ressource utilisateur
                return $this->sendResponse(
                    StatutEnum::SUCCESS,
                    new UserResource($user),
                    'Utilisateur récupéré avec succès.'
                );
            } catch (\Exception $e) {
                // Gestion des erreurs
                return $this->sendResponse(
                    StatutEnum::ECHEC,
                    null,
                    'Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage()
                );
            }
        }
}