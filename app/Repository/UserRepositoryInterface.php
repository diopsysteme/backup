<?php
namespace App\Repository;

use App\Models\Client;
use App\Models\User;
use App\Models\Role;

interface UserRepositoryInterface
{
    public function findClientById($id);
    public function createUser(array $data);
    public function findRoleByLabel($label);
    public function associateUserWithClient(User $user, Client $client);
    public function associateRoleWithUser(Role $role, User $user);
    public function getUsersWithFilters(array $data);
}
