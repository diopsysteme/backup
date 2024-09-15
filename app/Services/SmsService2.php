<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use RuntimeException;
use Twilio\Rest\Client;

class SmsService2 implements SmsServiceInterface
{
    protected $twilio;
protected $telephone;
    public function __construct()
    {
        $sid    = config('sms.twilio.ssid');
        $token  = config('sms.twilio.token');
        $this->telephone = config('sms.twilio.number');
        $this->twilio = new Client($sid, $token); // Initialisation du client Twilio dans le constructeur
    }

    /**
     *
     * @param string $from
     * @param string $to
     * @param string $messageText
     */
    public function sendMessage(string $telephone, string $to, string $messageText): void
    {
        // dd($this->telephone);
        try {
            $message = $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->telephone,
                    'body' => $messageText
                ]
            );

            \Log::info('SMS sent successfully', ['sid' => $message->sid]);
        } catch (ServiceException $e) {
            \Log::error('Failed to send SMS', ['error' => $e->getMessage()]);
            throw new ServiceException('Failed to send SMS: ' . $e->getMessage());
        }
    }
}
