<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\ProcessUserCreation;
use App\Jobs\UpdateUsersPhotoJob;
use App\Jobs\RetryCloudinaryUploadJob;

class RetryCloudinaryUploadCommand extends Command
{
    protected $signature = 'cloudinary:retry-upload';
    protected $description = 'Retry Cloudinary photo upload for users whose photos are not hosted on Cloudinary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Rechercher les utilisateurs dont la photo n'est pas sur Cloudinary
        

        UpdateUsersPhotoJob::dispatch();
          
        $this->info('Cloudinary photo re-upload jobs dispatched.');
    }
}
