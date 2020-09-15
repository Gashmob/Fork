<?php

namespace Fork\Kernel;

use Exception;
use Fork\Database\DatabaseConnection;
use Fork\Database\DatabaseCredentials;
use Fork\Database\Exceptions\ConnectionFailedException;
use Fork\Request\Cookie;
use Fork\Request\Request;
use Fork\Request\Session;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
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
     * @var array
     */
    private $args = [];

    /**
     * Kernel constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->args[get_class($request)] = $request;
        $session = new Session();
        $this->args[get_class($session)] = $session;
        $cookie = new Cookie();
        $this->args[get_class($cookie)] = $cookie;

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
    }


    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function handle()
    {
        $router = new Router();
        $request = $this->args[Request::class];

        $method = $router->getMethod($request->getRoute());
        $controller = $this->constructController($method->getDeclaringClass());
        $method->setAccessible(true);
        $result = $this->invokeMethod($method, $controller);

        if ($result instanceof Response) {
            echo $result->getContent();
        } elseif ($result instanceof RedirectResponse) {
            $routeName = $result->getRouteName();

            $redirect = $router->getRoute($routeName);

            $this->args[Request::class] = new Request($request->getArray(), $request->postArray(), $redirect);
            $this->handle();
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
     * @return mixed
     * @throws ReflectionException
     */
    private function invokeMethod(ReflectionMethod $reflection, $controller)
    {
        $parameters = $reflection->getParameters();

        return $reflection->invokeArgs($controller, $this->getArgs($parameters));
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @return array
     * @throws ReflectionException
     */
    private function getArgs(array $parameters)
    {
        $args = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (isset($this->args[$type->getName()])) {
                $args[] = $this->args[$type->getName()];
            } else {
                if ($parameter->isOptional()) {
                    $args[] = $parameter->getDefaultValue();
                } else {
                    $args[] = '';
                }
            }
        }

        return $args;
    }
}