<?php

namespace Gashmob\Fork\responses;

use Gashmob\Fork\services\RequestService;
use Gashmob\Fork\services\ServiceManager;
use Gashmob\Fork\services\SessionService;

class RedirectResponse extends AbstractResponse
{
    const SESSION_KEY = '__redirect_args';

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $args;

    /**
     * @param string $route
     */
    public function __construct($route, $args = [])
    {
        $this->route = $route;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function handle()
    {
        if (!empty($this->args)) {
            /** @var SessionService $session */
            $session = ServiceManager::getService(SessionService::class);
            $session->set(self::SESSION_KEY, $this->args);
        }

        /** @var RequestService $request */
        $request = ServiceManager::getService(RequestService::class);
        header('Location: ' . $request->getBaseUri() . $this->route);

        return '';
    }
}