<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RepExcept extends Facade{
    protected static function getFacadeAccessor(){
        return 'rep_except';
    }
}