<?php

namespace App\Services;

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;

class SmsService implements SmsServiceInterface
{
    private $smsApi;

    public function __construct()
    {
        $configuration = new Configuration(
            host: config('sms.infobib.url'),
            apiKey: config('sms.infobib.key'),  
        );
        $this->smsApi = new SmsApi($configuration);
    }
    public function sendMessage(string $from, string $to, string $messageText): void
{
    $destination = new SmsDestination($to);
    $message = new SmsTextualMessage([$destination]);
    $message->setFrom($from);
    $message->setText($messageText);
    $request = new SmsAdvancedTextualRequest([$message]);
    try {
        $response = $this->smsApi->sendSmsMessage($request);
        echo "Message envoyé avec succès : " . $response->getMessages()[0]->getStatus()->getName();
    } catch (\Exception $e) {
        echo "Erreur lors de l'envoi du message : " . $e->getMessage();
    }
}

    
    
    // public function sendMessage(string $from, string $to, string $messageText): void
    // {
    //     // Créer la destination du message
    //     $destination = new SmsDestination(
    //          $to
    //     );

    //     // Créer le message avec son contenu textuel
    //     $message = new SmsTextualMessage([
    //         'from' => $from,
    //         'destinations' => [$destination],
    //         'text' => $messageText
    //     ]);

    //     // Créer la requête avancée pour l'envoi du message
    //     $request = new SmsAdvancedTextualRequest([
    //         'messages' => [$message]
    //     ]);

    //     try {
    //         // Utilisation de SmsAdvancedTextualRequest dans la méthode sendSmsMessage
    //         $response = $this->smsApi->sendSmsMessage($request);
    //         echo "Message envoyé avec succès : " . $response->getMessages()[0]->getStatus()->getName();
    //     } catch (\Exception $e) {
    //         // Gérer l'exception
    //         echo "Erreur lors de l'envoi du message : " . $e->getMessage();
    //     }
    // }
}
