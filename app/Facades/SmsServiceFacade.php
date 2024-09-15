<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SmsServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms-service'; // Le nom qui sera utilisé pour la facade
    }
}
