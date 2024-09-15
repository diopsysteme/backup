<?php

namespace App\Jobs;

use App\Exceptions\ServiceException;
use App\Facades\Pdf;
use App\Models\User;
use App\Facades\Mailer;
use Illuminate\Bus\Queueable;
use App\Facades\FileUploadCloud;
use Illuminate\Support\Facades\Log;
use App\Events\UploadUserPhotoEvent;
use App\Services\ContentHtmlService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUserCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('User created and email sent async:', ['user' => $this->user]);
        Log::info("UploadUserPhothjhdjsho");
        try {
            
            //  dd($this->user->photo);
            $photo = $this->user->photo;
            $photoPath = FileUploadCloud::uploadFile($photo, 'image');
            $user = $this->user;
            $user->photo = $photoPath;
            $user->save();
            // dd($user);
        } catch (ServiceException $th) {
            Log::error('Error while uploading user photo to Cloudinary:', ['error' => $th->getMessage(), 'user' => $this->user]);
        }

    }

}
