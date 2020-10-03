<?php


namespace Fork\Kernel;


use Exception;
use Fork\Annotations\RouteAnnotationReader;
use Fork\Controller\ControllerHandler;
use Fork\Kernel\Exceptions\MissingArgumentException;
use Fork\Kernel\Exceptions\RedirectionNotFoundException;
use Fork\Kernel\Exceptions\RouteNotFoundException;
use ReflectionException;
use ReflectionMethod;

class Router
{
    const METHOD = 'method';
    const ARGS = 'args';

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
     * @return array
     * @throws RouteNotFoundException
     */
    public function getMethod(string $route)
    {
        $tab = $this->cutRoute($route);

        $args = [];

        if (count($tab) > 1 && $tab[count($tab) - 1] == '/') {
            $tab = array_slice($tab, 0, count($tab) - 1);
        }
        $routes = $this->routes;

        $result = '';
        for ($i = 0; $i < count($tab); $i++) {
            $routeT = [];
            foreach ($routes as $r => $method) {
                if ($r == $tab[$i]) {
                    if (is_array($method)) {
                        if ($i < count($tab) -1) { // Si on n'a pas fini sur la route recherché
                            $routeT = array_merge($routeT, $method);
                        } elseif (is_int(key($method))) {
                            $result = $method[key($method)];
                        }
                    } elseif ($i == count($tab) -1) { // Si on est à la fin du tableau
                        $result = $method; // On a trouvé !
                    }
                } elseif ($this->isVariableRoute($r)) {
                    $args[$this->getVariableRouteName($r)] = substr($tab[$i], 1);
                    if (is_array($method)) {
                        $routeT = array_merge($routeT, $method);
                    } else {
                        $result = $method;
                    }
                }
            }
            $routes = $routeT;
        }
        if (is_array($result) || $result == '') throw new RouteNotFoundException($route);

        return [
            self::METHOD => $result,
            self::ARGS => $args
        ];
    }

    /**
     * @param string $routeName
     * @param array $args
     * @return string
     * @throws RedirectionNotFoundException
     * @throws MissingArgumentException
     */
    public function getRoute(string $routeName, array $args)
    {
        if (isset($this->routeNames[$routeName])) {
            $route = $this->routeNames[$routeName];
            $tab = $this->cutRoute($route);

            for ($i = 0; $i < count($tab); $i++) {
                if ($this->isVariableRoute($tab[$i])) {
                    $name = $this->getVariableRouteName($tab[$i]);
                    if (isset($args[$name])) {
                        $tab[$i] = '/' . $args[$name];
                    } else throw new MissingArgumentException($routeName, substr($tab[$i], 1));
                }
            }
            return implode($tab);

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
                if (is_array($array[$route[0]])) {
                    $array[$route[0]] = $this->getArray(array_slice($route, 1), $method, $array[$route[0]]);
                } else {
                    $array[$route[0]] = $this->getArray(array_slice($route, 1), $method, [$array[$route[0]]]);
                }
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
                    $word = $c;
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

    /**
     * @param string $route
     * @return bool
     */
    private function isVariableRoute(string $route)
    {
        $len = strlen($route);
        return $len >= 4 ? $route[1] == '{' && $route[$len - 1] == '}' : false;
    }

    /**
     * @param string $route
     * @return string
     */
    private function getVariableRouteName(string $route)
    {
        if ($this->isVariableRoute($route)) return substr($route, 2, strlen($route) - 3);
        else return '';
    }
}