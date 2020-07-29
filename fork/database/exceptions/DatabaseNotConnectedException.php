<?php


namespace Fork\Database\Exceptions;


use Exception;

class DatabaseNotConnectedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Vous n\'êtes pas connecté à la base de données');
    }
}