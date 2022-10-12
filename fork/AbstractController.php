<?php

namespace Gashmob\Fork;

use Gashmob\Fork\exceptions\RouteNotFoundException;
use Gashmob\Fork\responses\RedirectResponse;
use Gashmob\Fork\services\RouterService;
use Gashmob\Fork\services\ServiceManager;

class AbstractController
{
    /**
     * @param string $route
     * @return RedirectResponse
     * @throws RouteNotFoundException
     */
    private function redirect($route)
    {
        /** @var RouterService $router */
        $router = ServiceManager::getService(RouterService::class);

        if (!$router->hasRoute($route)) {
            throw new RouteNotFoundException($route);
        }

        return new RedirectResponse($route);
    }
}