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
    public function __construct(string $routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }
}