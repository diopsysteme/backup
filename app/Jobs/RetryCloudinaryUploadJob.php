<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Facades\FileUploadCloud;
use Illuminate\Support\Facades\Log;
use App\Events\UploadUserPhotoEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RetryCloudinaryUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Vérifier si le lien de la photo de l'utilisateur n'est pas un lien Cloudinary
        if (!str_contains($this->user->photo, 'cloudinary.com')&& Storage::exists($this->user->photo)) {
            log::info("Cloudinary retrying you see me???");
            event(new UploadUserPhotoEvent($this->user, $this->user->photo));
        }else{
            Log::info('User photo is already hosted on Cloudinary or not found:', ['user' => $this->user]);
        }
    }
}
