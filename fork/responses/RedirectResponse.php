<?php

namespace Gashmob\Fork\responses;

class RedirectResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $route;

    /**
     * @param string $route
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
}