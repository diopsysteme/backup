<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Enums\StatutEnum;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can create a user with a specific role.
     *
     * @param  \App\Models\User  $user
     * @param  string  $role
     * @return bool
     */
    public function create(User $user, $role)
    {
        dd("ksdjks");
        // Check if the authenticated user is an admin
        if ($user->hasRole(RoleEnum::ADMIN)) {
            dd("dd");
            return in_array($role, [RoleEnum::ADMIN->value, RoleEnum::BOUTIQUE->value]);
        }

        // Check if the authenticated user is a boutiquier
        if ($user->hasRole(RoleEnum::BOUTIQUE->value)) {
            dd("dd");
            return $role === RoleEnum::CLIENT->value;
        }

        return false;
    }
    public function viewAny(User $user)
    {
       return $user->roleValue()==RoleEnum::ADMIN->value;
    }
    public function view(User $user,$thing){
        return $user->id == $thing->user_id || $user->client->id == $thing->client_id ;
    }
}

