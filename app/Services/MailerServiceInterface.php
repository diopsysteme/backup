<?php
namespace App\Services;
use App\Models\Client;

interface MailerServiceInterface{
    public function sendEmail($mail,$attachments);
    // public function sendSMS($to, $message);
}