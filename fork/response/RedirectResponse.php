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
     * @var array
     */
    private $args;

    /**
     * RedirectResponse constructor.
     * @param string $routeName
     * @param array $args
     */
    public function __construct(string $routeName, array $args = [])
    {
        $this->routeName = $routeName;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}