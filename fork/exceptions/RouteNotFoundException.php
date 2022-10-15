<?php

namespace Gashmob\Fork\exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    /**
     * @param string $route
     */
    public function __construct($route)
    {
        parent::__construct('Route not found : ' . $route);
    }
}