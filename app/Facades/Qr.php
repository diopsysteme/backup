<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Qr extends Facade{
    protected static function getFacadeAccessor()
    {
        return 'qrcode';
    }
}