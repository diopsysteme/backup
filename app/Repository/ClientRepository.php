<?php
namespace App\Repository;

use App\Exceptions\RepositoryException;
use App\Facades\FileUploadCloud;
use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Facades\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientRepository implements ClientRepositoryInterface
{
    public function all()
    {
        return Client::all();
    }

    public function findClientById($id)
    {
        try {
            return Client::findOrFail($id);
        } catch (RepositoryException $th) {
            throw new RepositoryException('user not found: '. $th->getMessage(), '');
        }
    }

    public function filter($request)
    {
        $query = Client::query();

        return $query->filterByRequest($request)->get();
    }

    public function createClient(array $clientData, array $userData = null)
    {
        DB::beginTransaction();
        try {
            $client = Client::create($clientData);
            request()->client2Save=$client;
            if (!empty($userData)) {
                 User::create($userData);
            }
            DB::commit();
            return $client;
        } catch (RepositoryException $e) {
            DB::rollBack();
            throw new ("Erreur lors de la création du client : " . $e);
        }
    }


    public function update($id, $data)
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client;
    }

    public function delete($id)
    {
        return Client::findOrFail($id)->delete();
    }
    public function getClientWithUser($id)
    {
        return Client::with('user')->findOrFail($id);
    }


    public function findClientWithDebts($clientId)
    {
        return Client::with('dettes')->findOrFail($clientId);
    }



    public function findByTelephone($telephone)
    {
        return Client::where('telephone', $telephone)->firstOrFail();
    }
   
}
