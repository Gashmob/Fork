<?php

namespace Fork\Kernel;

use Exception;
use Fork\Annotations\RouteAnnotationReader;
use Fork\ControllerHandler;
use Fork\Database\DatabaseConnection;
use Fork\Database\DatabaseCredentials;
use Fork\Database\Exceptions\ConnectionFailedException;
use Fork\Request\Request;
use Fork\Request\Session;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Fork\Response\TemplateResponse;
use ReflectionException;
use ReflectionMethod;
use YamlEditor\Exceptions\PathNotFoundException;
use YamlEditor\YamlArray;
use YamlEditor\YamlFile;

/**
 * Class Kernel
 * The programme heart : is here that the requests is treat for render a response
 * @package Fork
 */
class Kernel
{
    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        $config = new YamlArray(new YamlFile('config/config.yml'));
        try {
            DatabaseConnection::connect(new DatabaseCredentials(
                $config->get('database.credentials.host'),
                $config->get('database.credentials.user'),
                $config->get('database.credentials.password'),
                $config->get('database.credentials.dbName'),
                $config->get('database.credentials.port')
            ));
        } catch (ConnectionFailedException $e) {
            die($e);
        } catch (PathNotFoundException $e) {
            die($e);
        }

        Session::start();
    }


    /**
     * @param Request $request
     * @throws ReflectionException
     * @throws Exception
     */
    public function handle($request)
    {
        $reader = new RouteAnnotationReader();
        $controllers = (new ControllerHandler())->getControllers();
        $routes = [];
        foreach ($controllers as $controller) {
            $routes = array_merge($routes, $reader->getRoutes($controller));
        }


        foreach ($routes as $route) {
            if ($route['route'] === $request->getRoute()) {
                $method = $route['method'];
                if ($method instanceof ReflectionMethod) {
                    $controller = $method->getDeclaringClass()->newInstance();
                    $method->setAccessible(true);
                    $result = $method->invoke($controller);

                    if ($result instanceof Response) {
                        echo $result->getContent();
                    } elseif ($result instanceof TemplateResponse) {
                        include_once "Kernel.php";
                    } elseif ($result instanceof RedirectResponse) {
                        $routeName = $result->getRouteName();

                        $redirect = '';
                        foreach ($routes as $newRoute) {
                            if ($newRoute['routeName'] === $routeName) {
                                $redirect = $newRoute['route'];
                            }
                        }

                        $this->handle(new Request($request->getArray(), $request->postArray(), $redirect));
                    }
                }
            }
        }
    }
}