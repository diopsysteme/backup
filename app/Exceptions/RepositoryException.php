<?php

namespace App\Exceptions;

use Exception;

class RepositoryException extends Exception
{
    protected $repository;

    public function __construct($message = "", $repository = "", $code = 0, Exception $previous = null)
    {
        $this->repository = $repository;
        parent::__construct($message, $code, $previous);
    }

    public function getRepository()
    {
        return $this->repository;
    }
}
