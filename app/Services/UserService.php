<?php
namespace App\Services;

use App\Exceptions\ServiceException;
use App\Repository\UserRepositoryInterface;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function storeUserSaveClient(array $data)
    {
        try {
            $request = request();

            if ($request->has('idclient')) {
                $client = $this->userRepository->findClientById($request->get('idclient'));
                if ($client->user) {
                    return ["statut" => "echec", "message" => "Client already have user account"];
                }
                $request["client2Save"] = $client;
            }
           return $this->userRepository->createUser(collect($data)->except(['password_confirmation'])->toArray());

        } catch (ServiceException $e) {
            throw new ServiceException('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
        }
    }
    public function getUsersWithFilters(array $filters)
    {
        return $this->userRepository->getUsersWithFilters($filters);
    }
}
