<?php


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