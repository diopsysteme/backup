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

class UpdateUsersPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('User created and email sent async:', []);
        Log::info("UploadUserPhothjhdjsho");
        try {
            $users = User::whereNotNull('photo')->where('photo', 'not like', '%cloudinary.com%')->get();

        if ($users->isEmpty()) {
            Log::info(message: 'No users found for re-upload.');
            return;
        }

        foreach ($users as $user) {
            Log::info("UploadUserPhothjhdjsho".$user->nom);
            $photoPath = FileUploadCloud::uploadFile($user->photo, 'image');
            $user->photo = $photoPath;
            $user->save();
            Log::info("done for ".$user->nom);

        }
        } catch (ServiceException $th) {
            Log::error('Error while uploading user photo to Cloudinary:', ['error' => $th->getMessage()]);
        }

    }

}
