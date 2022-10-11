<?php

namespace Gashmob\Fork;

use Exception;
use Gashmob\Fork\services\RequestService;
use Gashmob\Fork\services\RouterService;
use Gashmob\Fork\services\ServiceManager;
use Gashmob\Mdgen\MdGenEngine;

class Kernel
{
    public function __construct()
    {
        ServiceManager::initialize();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        // Get requested route
        $route = ServiceManager::getService(RequestService::class)->getUri();

        // Get file path
        /** @var RouterService $router */
        $router = ServiceManager::getService(RouterService::class);
        if (!$router->hasRoute($route)) {
            throw new Exception('Page not found : ' . $route);
        }
        $file = $router->getRoute($route);

        // Pre-render file
        $engine = new MdGenEngine();
        $engine->basePath(__DIR__ . '/../view/templates');
        $engine->includePath(__DIR__ . '/../view/components');
        $values = $engine->preRender($file);

        // Call controller if specified
        // TODO

        // Render file
        return $engine->render($file, $values);
    }
}