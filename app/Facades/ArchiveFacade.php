<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ArchiveFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'archiveService'; 
    }
}
