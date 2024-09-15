<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FileUploadCloud extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fileupload2';
    }
}
