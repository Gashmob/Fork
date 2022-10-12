<?php

namespace Gashmob\Fork\responses;

use Gashmob\Fork\services\RequestService;
use Gashmob\Fork\services\ServiceManager;
use Gashmob\Fork\services\SessionService;

final class RedirectResponse extends AbstractResponse
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
     * @param array $args
     */
    public function __construct(string $route, array $args = [])
    {
        $this->route = $route;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function handle(): string
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