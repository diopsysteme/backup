<?php

namespace App\Listeners;

use App\Exceptions\ServiceException;
use App\Facades\Qr;
use App\Facades\Pdf;
use App\Facades\Mailer;
use App\Jobs\CreateQRNmailJob;
use Illuminate\Support\Facades\Log;
use App\Services\ContentHtmlService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateQRNMAIL
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
    public function handle(object $event): void
    {
       if($event->user->client){
        try {
            CreateQRNmailJob::dispatch($event->user);
        } catch (ServiceException $th)
        {
            Log::error('Erreur lors de la création du QR et du mail : '. $th->getMessage());
        }
       }
    }
}
