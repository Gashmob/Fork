<?php


namespace Fork\kernel\exceptions;


use Exception;

class RouteNotFoundException extends Exception
{
    /**
     * RouteNotFoundException constructor.
     * @param string $route
     */
    public function __construct(string $route)
    {
        parent::__construct('La route n\'a pas été trouvée : ' . $route);
    }
}