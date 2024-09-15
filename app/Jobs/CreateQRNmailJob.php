<?php

namespace App\Jobs;

use App\Exceptions\ServiceException;
use App\Facades\Qr;
use App\Facades\Pdf;
use App\Facades\Mailer;
use Illuminate\Bus\Queueable;
use App\Services\ContentHtmlService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateQRNmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $path;
    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        // dd($user);
        $this->user = $user;
        $this->path =$user->photo ;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       try {
        $user= $this->user;
        $path = $this->path;
    
            $qrcode = Qr::generateQr("Telephone: " . $user->telephone);
    
            $client = $user->client;
            $client->qrcode = $qrcode;
    
            $client->save();
            $photoContent = Storage::get($path);
            $imageBase64 = base64_encode($photoContent);
            $tab = ['client' => $user->nom . ' ' . $user->prenom, 'qrcode' => $client->qrcode, 'image' => $imageBase64];
    
            $html = ContentHtmlService::generateHtml($tab);
    
            $pdfContent = Pdf::generatePdf($html);
    
            Mailer::sendEmail($user->login, $pdfContent);
       } catch (ServiceException $th) {

            throw new ServiceException('Erreur lors de la création du QR et du mail : '. $th->getMessage());}
    }
}
