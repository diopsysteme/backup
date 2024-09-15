<?php

namespace App\Policies;

use App\Enums\CategoryEnum;
use App\Enums\RoleEnum;
use App\Exceptions\ServiceException;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // dd( RoleEnum::BOUTIQUE->value);
        return $user->roleValue() == RoleEnum::BOUTIQUE->value;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Client $client): bool
    {
        if ($user->roleValue() == RoleEnum::BOUTIQUE->value)
            return true;
        if ($user->client()->first()){
            return $user->client()->first()->id == $client->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $client): bool
    {
        dd($user);
        return $user->roleValue() == RoleEnum::ADMIN->value || $user->client()->id == $client->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Client $client): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Client $client): bool
    {
        //
    }

    public function canDemande(User $user): bool{
        // dd($client->client);
        $client = $user->client;
        if(!$client){
            return false;
        }
        // dd($client->category_label);
        try {
            if($client->category_label==CategoryEnum::Bronze->value){
                return $client->can_bronze;
            }
            if($client->category_label==CategoryEnum::Silver->value){
                // dd("ss");
                return $client->can_silver;
            }
            // dd($client->can_gold);
            return $client->can_gold;
        } catch (ServiceException $th) {
            throw new ServiceException("erreur".$th->getMessage());
        }
    }
    public function onlyClient(User $user): bool{
        // dd($user->client);
        if($user->client){
            return true;
        }
        return false;
    }
}
