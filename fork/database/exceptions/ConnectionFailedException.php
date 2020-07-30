<?php


namespace Fork\Database\Exceptions;


use Exception;

/**
 * Class ConnectionFailedException
 * Throw if the connection to the database failed
 * @package Fork\Database\Exceptions
 */
class ConnectionFailedException extends Exception
{
    public function __construct()
    {
        parent::__construct('La connection à la base de donnée a échoué');
    }
}