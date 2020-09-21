<?php


namespace Fork\kernel\Exceptions;


use Exception;

class MissingArgumentException extends Exception
{
    /**
     * MissingArgumentException constructor.
     * @param string $routeName
     * @param string $arg
     */
    public function __construct(string $routeName, string $arg)
    {
        parent::__construct("Il manque l'argument $arg pour la route $routeName");
    }
}