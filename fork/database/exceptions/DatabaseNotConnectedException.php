<?php


namespace Fork\Database\Exceptions;


use Exception;

/**
 * Class DatabaseNotConnectedException
 * Throw if we aren't connected to the database
 * @package Fork\Database\Exceptions
 */
class DatabaseNotConnectedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Vous n\'êtes pas connecté à la base de données');
    }
}