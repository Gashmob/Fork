<?php


namespace Fork\Database\Exceptions;


use Exception;

class ConnectionFailedException extends Exception
{
    public function __construct()
    {
        parent::__construct('La connection à la base de donnée a échoué');
    }
}