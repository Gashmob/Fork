<?php

namespace Gashmob\Fork;

use Gashmob\Fork\exceptions\RouteNotFoundException;
use Gashmob\Fork\responses\RedirectResponse;
use Gashmob\Fork\services\RouterService;
use Gashmob\Fork\services\ServiceManager;

abstract class AbstractController
{
    public function __construct()
    {
    }

    /**
     * @param string $route
     * @return RedirectResponse
     * @throws RouteNotFoundException
     */
    protected function redirect($route)
    {
        /** @var RouterService $router */
        $router = ServiceManager::getService(RouterService::class);

        if (empty($route)) {
            $route = '/';
        } else if ($route[0] !== '/') {
            $route = '/' . $route;
        }

        if (!$router->hasRoute($route)) {
            throw new RouteNotFoundException($route);
        }

        return new RedirectResponse($route);
    }
}