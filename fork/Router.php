<?php

namespace Gashmob\Fork;

use Gashmob\Mdgen\exceptions\FileNotFoundException;
use Gashmob\Mdgen\exceptions\ParserStateException;
use Gashmob\Mdgen\MdGenEngine;


/**
 * This class allow Fork to fetch and render the page corresponding to the request
 */
class Router
{
    /**
     * Key for MdGenEngine to specify the controller to use
     */
    const CONTROLLER_KEY = 'controller';

    /**
     * Location of pages directory containing all the pages
     */
    const PAGE_DIRECTORY = __DIR__ . '/../view/pages/';

    /**
     * @param string $route
     */
    public function __construct($route)
    {
        $routes = $this->fetchRoutes();
        $file = $this->getFileFromRoute($routes, $route);

        if ($file === null) {
            echo '404'; // TODO : render the 404 file
        } else {
            try {
                $this->render($file);
            } catch (FileNotFoundException $e) {
                // TODO : Handle this exception
            } catch (ParserStateException $e) {
                // TODO : Handle this exception
            }
        }
    }

    /**
     * @return array
     */
    private function fetchRoutes()
    {
        // TODO : get all routes from view/pages

        return [];
    }

    /**
     * @param array $routes
     * @param string $route
     * @return string|null
     */
    private function getFileFromRoute($routes, $route)
    {
        // TODO : get file from route

        return null;
    }

    /**
     * @param string $file
     * @return void
     * @throws FileNotFoundException|ParserStateException
     */
    private function render($file)
    {
        $engine = new MdGenEngine();
        $values = $engine->preRender($file);

        if (in_array(self::CONTROLLER_KEY, $values)) {
            $controller = new $values[self::CONTROLLER_KEY](); // TODO : Fetch constructor arguments
            // TODO : Call controller methods depending on request method
            $values = array_merge($values, $controller->get($values)); // TODO : Fetch get() arguments
        }

        // Echo final result
        echo $engine->render($file, $values);
    }
}