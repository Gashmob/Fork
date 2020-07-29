<?php

namespace Fork;

use Fork\Database\DatabaseConnection;
use Fork\Database\DatabaseCredentials;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Fork\Response\TemplateResponse;
use Fork\Yaml_Editor\YamlArray;
use Fork\Yaml_Editor\YamlFile;

class Kernel
{
    /**
     * @var array
     */
    private $router;

    /**
     * Kernel constructor.
     * @param array $router
     */
    public function __construct(array $router)
    {
        $this->router = $router;

        $config = new YamlArray(new YamlFile('config/config.yml'));
        try {
            DatabaseConnection::connect(new DatabaseCredentials(
                $config->get('database.credentials.host'),
                $config->get('database.credentials.user'),
                $config->get('database.credentials.password'),
                $config->get('database.credentials.dbName'),
                $config->get('database.credentials.port')
            ));
        } catch (Database\Exceptions\ConnectionFailedException $e) {
            die($e);
        } catch (Yaml_Editor\Exceptions\PathNotFoundException $e) {
            die($e);
        }

        Session::start();
    }


    /**
     * @param string $request
     */
    public function handle($request)
    {
        if (isset($this->router[$request])) {
            if (isset($this->router[$request]['controller'])) {
                $control = $this->router[$request]['controller'];

                if ($control instanceof Response) {
                    echo $control->getContent();
                } elseif ($control instanceof TemplateResponse) {
                    include_once "{$control->getTemplate()}";
                } elseif ($control instanceof RedirectResponse) {
                    $routeName = $control->getRouteName();

                    $redirect = '';
                    foreach ($this->router as $route => $properties) {
                        if (isset($properties['name'])) {
                            if ($properties['name'] == $routeName) {
                                $redirect = $route;
                            }
                        }
                    }

                    $this->handle($redirect);
                }
            }
        }
    }
}