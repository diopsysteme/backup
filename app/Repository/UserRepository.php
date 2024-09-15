<?php
namespace App\Repository;

use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    public function findClientById($id)
    {
        $client = Client::find($id);
        if (!$client) {
            throw new ModelNotFoundException("Client not found.");
        }
        return $client;
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function findRoleByLabel($label)
    {
        return Role::where('label', $label)->firstOrFail();
    }

    public function associateUserWithClient(User $user, Client $client)
    {
        $user->client()->save($client);
    }

    public function associateRoleWithUser(Role $role, User $user)
    {
        $role->users()->save($user);
    }
    public function getUsersWithFilters(array $filters)
    {
        $query = User::query();

        if (isset($filters['role'])) {
            $roleName = $filters['role'];
            $query->whereHas('role', function ($q) use ($roleName) {
                $q->where('label', $roleName);
            });
        }

        if (isset($filters['active'])) {
            $active = $filters['active'] === 'oui' ? 1 : 0;
            $query->where('active', $active);
        }

        return $query->paginate(10);
    }
}
