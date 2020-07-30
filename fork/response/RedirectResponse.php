<?php

namespace Fork\Response;

/**
 * Class RedirectResponse
 * Send a redirection to a certain route
 * @package Fork\Response
 */
class RedirectResponse
{
    /**
     * @var string
     */
    private $routeName;

    /**
     * RedirectResponse constructor.
     * @param string $routeName
     */
    public function __construct($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }
}