<?php

namespace Gashmob\Fork;

use Exception;
use Gashmob\Fork\responses\AbstractResponse;
use Gashmob\Fork\services\RequestService;
use Gashmob\Fork\services\RouterService;
use Gashmob\Fork\services\ServiceManager;
use Gashmob\Mdgen\MdGenEngine;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

final class Kernel
{
    const CONTROLLER_TAG = 'controller';

    public function __construct()
    {
        ServiceManager::initialize();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render(): string
    {
        // Get requested route
        /** @var RequestService $request */
        $request = ServiceManager::getService(RequestService::class);
        $route = $request->getUri();

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
        if (isset($values[self::CONTROLLER_TAG])) {
            $controller = $values[self::CONTROLLER_TAG];
            $control = $this->constructController($controller);

            switch ($request->getMethod()) {
                case RequestService::METHOD_POST:
                    $response = $this->callControllerMethod($control, 'post', $values);
                    break;

                case RequestService::METHOD_GET:
                default:
                    $response = $this->callControllerMethod($control, 'get', $values);
                    break;
            }

            if ($response instanceof AbstractResponse) {
                return $response->handle();
            }
            $values = array_merge($values, $response);
        }

        // Render file
        return $engine->render($file, $values);
    }

    /**
     * @param string $controller
     * @return object
     * @throws ReflectionException
     */
    private function constructController(string $controller)
    {
        $class = new ReflectionClass($controller);

        $parameters = $class->getConstructor()->getParameters();

        return $class->newInstanceArgs($this->getArgs($parameters));
    }

    /**
     * @param object $controller
     * @param string $method
     * @param array $args
     * @return array|AbstractResponse
     * @throws ReflectionException
     */
    private function callControllerMethod($controller, string $method, array $args)
    {
        $method = new ReflectionMethod($controller, $method);

        return $method->invokeArgs($controller, $this->getArgs($method->getParameters(), $args));
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @param array $args
     * @return array
     */
    private function getArgs(array $parameters, array $args = []): array
    {
        $result = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            $name = $parameter->getName();

            if (ServiceManager::hasService((string)$type)) {
                $result[] = ServiceManager::getService((string)$type);
            } else if (isset($args[$name])) {
                $result[] = $args[$name];
            } else {
                $result[] = null;
            }
        }

        return $result;
    }
}