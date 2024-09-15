<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SerExcept extends Facade{
    protected static function getFacadeAccessor(){
        return 'ser_except';
    }
}