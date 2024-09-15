<?php

namespace App\Observers;

use App\Events\CreateClientEvent;
use Log;
use App\Facades\Pdf;
use App\Models\Role;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Facades\Mailer;
use App\Jobs\ProcessUserCreation;
use App\Events\UploadUserPhotoEvent;
use App\Repository\ClientRepository;
use App\Services\ContentHtmlService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $request = request();

        if ($request->client2Save) {

            $client = $request->client2Save;
            $role = Role::where('label', RoleEnum::CLIENT)->first();
            $role->users()->save($user);
            $client->user()->associate($user);
            $client->save();

        } else {
            $roleUser = $request->idrole;
            $role = Role::where('id', $roleUser)->first();
            $role->users()->save($user);
        }
        $photo = $request->file('user.photo');
        if ($photo) {
            $photoPath = $photo->store('temp_photos');
            $user->photo = $photoPath;
            $user->photo2 = $photoPath;
            $user->save();
            event(new CreateClientEvent($user));
        }
    }


    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
