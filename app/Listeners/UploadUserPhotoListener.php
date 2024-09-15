<?php

namespace App\Listeners;

use App\Facades\FileUpload;
use App\Facades\FileUploadCloud;
use App\Jobs\ProcessUserCreation;
use Illuminate\Support\Facades\Log;
use App\Events\UploadUserPhotoEvent;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadUserPhotoListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle( $event)
    {
        try {
            ProcessUserCreation::dispatch($event->user);

        } catch (ServiceException $e) {
            Log::error("Erreur lors de l'upload de la photo : ", [$e->getMessage()]);
        }
    }
}

