<?php

namespace App\Exceptions;

use Exception;

class ServiceException extends Exception
{
    // Vous pouvez ajouter des propriétés personnalisées ou des méthodes si nécessaire
    protected $service;

    public function __construct($message = "", $service = "", $code = 0, Exception $previous = null)
    {
        $this->service = $service;
        parent::__construct($message, $code, $previous);
    }

    public function getService()
    {
        return $this->service;
    }
}
