<?php


namespace Fork\kernel;


use Exception;
use Fork\Annotations\RouteAnnotationReader;
use Fork\Controller\ControllerHandler;
use Fork\kernel\exceptions\RedirectionNotFoundException;
use Fork\kernel\exceptions\RouteNotFoundException;
use ReflectionException;
use ReflectionMethod;

class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var array
     */
    private $routeNames;

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function __construct()
    {
        $reader = new RouteAnnotationReader();
        $controllers = (new ControllerHandler())->getControllers();
        $routes = [];
        foreach ($controllers as $controller) {
            $routes = array_merge($routes, $reader->getRoutes($controller));
        }

        $result = [];
        $this->routeNames = [];
        foreach ($routes as $route) {
            $result = $this->set($route[RouteAnnotationReader::ROUTE], $route[RouteAnnotationReader::METHOD], $result);
            $this->routeNames[$route[RouteAnnotationReader::ROUTE_NAME]] = $route[RouteAnnotationReader::ROUTE];
        }

        $this->routes = $result;
    }

    /**
     * @param string $route
     * @return ReflectionMethod
     * @throws RouteNotFoundException
     */
    public function getMethod(string $route)
    {
        $tab = $this->cutRoute($route);

        foreach ($tab as $word) {
            if (isset($value)) {
                if (isset($value[$word])) {
                    $value = $value[$word];
                } else throw new RouteNotFoundException($route);
            } else {
                if (isset($this->routes[$word])) {
                    $value = $this->routes[$word];
                } else throw new RouteNotFoundException($route);
            }
        }

        if (isset($value)) {
            if ($value instanceof ReflectionMethod) {
                return $value;
            } else throw new RouteNotFoundException($route);
        } else throw new RouteNotFoundException($route);
    }

    /**
     * @param string $routeName
     * @return string
     * @throws RedirectionNotFoundException
     */
    public function getRoute(string $routeName)
    {
        if (isset($this->routeNames[$routeName])) {
            return $this->routeNames[$routeName];
        } else throw new RedirectionNotFoundException($routeName);
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $route
     * @param ReflectionMethod $method
     * @param array $array
     * @return array
     */
    private function set(string $route, ReflectionMethod $method, array $array)
    {
        return $this->getArray($this->cutRoute($route), $method, $array);
    }

    /**
     * @param array $route
     * @param ReflectionMethod $method
     * @param array $array
     * @return array
     */
    private function getArray(array $route, ReflectionMethod $method, array $array)
    {
        if (count($route) == 1) {
            $array[$route[0]] = $method;
        } elseif (count($route) > 1) {
            if (isset($array[$route[0]])) {
                $array[$route[0]] = $this->getArray(array_slice($route, 1), $method, $array[$route[0]]);
            } else {
                $array = array_merge($array, $this->setArray($route, $method));
            }
        }

        return $array;
    }

    /**
     * @param array $route
     * @param ReflectionMethod $method
     * @return array
     */
    private function setArray(array $route, ReflectionMethod $method)
    {
        if (count($route) == 1) {
            $result[$route[0]] = $method;
        } elseif (count($route) > 1) {
            $result[$route[0]] = $this->setArray(array_slice($route, 1), $method);
        } else {
            $result = [$method];
        }

        return $result;
    }

    /**
     * @param string $route
     * @return array
     */
    private function cutRoute(string $route)
    {
        $result = [];

        $delimiter = '/';
        $word = '';
        for ($i = 0; $i < strlen($route); $i++) {
            $c = $route[$i];

            if ($c == $delimiter) {
                if (strlen($word) == 0) {
                    $word .= $c;
                } else { // strlen($word) > 0
                    $result[] = $word;
                    $word = '';
                }
            } else {
                $word .= $c;
            }
        }

        if (strlen($word) > 0) {
            $result[] = $word;
        }

        return $result;
    }
}