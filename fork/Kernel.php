<?php

namespace Fork;

use Fork\Database\DatabaseConnection;
use Fork\Database\DatabaseCredentials;
use Fork\Database\Exceptions\ConnectionFailedException;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Fork\Response\TemplateResponse;
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
        } catch (ConnectionFailedException $e) {
            die($e);
        } catch (PathNotFoundException $e) {
            die($e);
        }

        Session::start();
    }


    /**
     * @param Request $request
     */
    public function handle($request)
    {
        if (isset($this->router[$request->getRoute()])) {
            if (isset($this->router[$request->getRoute()]['controller'])) {
                $control = $this->router[$request->getRoute()]['controller'];

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