<?php


namespace Fork\kernel\exceptions;


use Exception;

class RedirectionNotFoundException extends Exception
{
    /**
     * RedirectionNotFoundException constructor.
     * @param string $routeName
     */
    public function __construct(string $routeName)
    {
        parent::__construct('La redirection n\'a pas été trouvée : ' . $routeName);
    }
}