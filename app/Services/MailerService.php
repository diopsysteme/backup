<?php
namespace App\Services;
use App\Services\MailerServiceInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Support\Facades\Mail;
class MailerService implements MailerServiceInterface{
public function sendEmail($mail, $pdfContent)
{
    Mail::send([], [], function ($messageBuilder) use ($mail, $pdfContent) {
        $messageBuilder->to($mail)
            ->subject("Votre Carte")
            ->html("Voici votre carte en pièce jointe");

        // Ajouter la pièce jointe PDF
        $messageBuilder->attachData($pdfContent, 'document.pdf', [
            'mime' => 'application/pdf',
        ]);
    });
}
}
