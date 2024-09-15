<?php
namespace App\Services;

interface QrServiceInterface{
    public function generateQr($data);
    // public function saveQr($qrData, $filename);
    // public function getQr($filename);
}
