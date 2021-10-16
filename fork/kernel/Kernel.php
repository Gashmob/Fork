<?php

namespace Fork\Kernel;

use Exception;
use Fork\Database\DatabaseConnection;
use Fork\Database\DatabaseCredentials;
use Fork\Database\Exceptions\ConnectionFailedException;
use Fork\kernel\Exceptions\RouteNotFoundException;
use Fork\Request\Cookie;
use Fork\Request\Request;
use Fork\Request\Session;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Fork\Twig\Twig;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use YamlEditor\Exceptions\PathNotFoundException;
use YamlEditor\YamlFile;

/**
 * Class Kernel
 * The programme heart : is here that the requests is treat for render a response
 * @package Fork
 */
class Kernel
{
    /**
     * @var string
     */
    public static $relativeUri;

    /**
     * @var string
     */
    public static $absoluteUri;

    /**
     * @var array
     */
    private $args = [];

    /**
     * Kernel constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $uri = $_SERVER['REQUEST_URI'];
        self::$relativeUri = substr($uri, 0, strlen($uri) - strlen($request->getRoute()));
        $uri = $_SERVER['HTTP_REFERER'];
        self::$absoluteUri = substr($uri, 0, strlen($uri) - 1) . self::$relativeUri;

        $this->args[get_class($request)] = $request;
        $session = new Session();
        $this->args[get_class($session)] = $session;
        $cookie = new Cookie();
        $this->args[get_class($cookie)] = $cookie;

        $config = (new YamlFile('config/config.yml'))->getYamlArray();
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
    }

    public function handle()
    {
        $router = new Router();
        $request = $this->args[Request::class];

        try {
            $route = $router->getMethod($request->getRoute());
            $method = $route[Router::METHOD];
            $controller = $this->constructController($method->getDeclaringClass());
            $method->setAccessible(true);
            $result = $this->invokeMethod($method, $controller, $route[Router::ARGS]);

            if ($result instanceof Response) {
                echo $result->getContent();
            } elseif ($result instanceof RedirectResponse) {
                $routeName = $result->getRouteName();
                $args = $result->getArgs();

                $redirect = $router->getRoute($routeName, $args);

                $uri = self::$relativeUri . $redirect;
                header('Location: ' . $uri);
            }
        } catch (RouteNotFoundException $e) {
            try {
                echo (new Twig())->getTwig()->render('errors/error404.html.twig', ['route' => $request->getRoute()]);
            } catch (Exception $er) {
                try {
                    echo (new Twig())->getTwig()->render('errors/exception.html.twig', ['exception' => $er]);
                } catch (Exception $err) {
                    die($err);
                }
            }
        } catch (Exception $e) {
            try {
                echo (new Twig())->getTwig()->render('errors/exception.html.twig', ['exception' => $e]);
            } catch (Exception $er) {
                die($er);
            }
        }
    }

    /**
     * @param ReflectionClass $reflection
     * @return object
     * @throws ReflectionException
     */
    private function constructController(ReflectionClass $reflection)
    {
        $parameters = $reflection->getConstructor()->getParameters();

        return $reflection->newInstanceArgs($this->getArgs($parameters));
    }

    /**
     * @param ReflectionMethod $reflection
     * @param object $controller
     * @param array $args
     * @return mixed
     * @throws ReflectionException
     */
    private function invokeMethod(ReflectionMethod $reflection, $controller, array $args)
    {
        $parameters = $reflection->getParameters();

        return $reflection->invokeArgs($controller, $this->getArgs($parameters, $args));
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @param array $args
     * @return array
     * @throws ReflectionException
     */
    private function getArgs(array $parameters, array $args = [])
    {
        $result = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (isset($args[$parameter->getName()])) {
                $result[] = $args[$parameter->getName()];
            } elseif (isset($this->args[$type->getName()])) {
                $result[] = $this->args[$type->getName()];
            } else {
                $result[] = $parameter->getDefaultValue();
            }
        }

        return $result;
    }
}