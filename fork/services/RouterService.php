<?php

namespace Gashmob\Fork\services;

final class RouterService
{
    const PAGES_DIR = __DIR__ . '/../../view/pages/';

    /**
     * @var array
     */
    private $routes;

    public function __construct()
    {
        $this->routes = $this->fetchRoutes();
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * Test if $route is a valid route.
     *
     * @param string $route
     * @return bool
     */
    public function hasRoute(string $route): bool
    {
        if (empty($route)) {
            $route = '/';
        } else if ($route[strlen($route) - 1] !== '/') {
            $route .= '/';
        }

        return isset($this->routes[$route]);
    }

    /**
     * @param string $route
     * @return string|null
     */
    public function getRoute(string $route)
    {
        if (empty($route)) {
            $route = '/';
        } else if ($route[strlen($route) - 1] !== '/') {
            $route .= '/';
        }

        return $this->routes[$route];
    }

    /**
     * Dump all existing routes.
     *
     * @return array
     */
    public function dump(): array
    {
        return $this->routes;
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $dir
     * @return array
     */
    private function fetchRoutes(string $dir = self::PAGES_DIR): array
    {
        if (file_exists($dir)) {
            $files = scandir($dir);
            $result = [];
            $baseUrl = '/' . substr($dir, strlen(self::PAGES_DIR));
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . $file)) {
                        $result = array_merge($result, $this->fetchRoutes($dir . $file . '/'));
                    } else if (is_file($dir . $file)) {
                        if (substr($file, -4) == '.mdt') {
                            if ($file == 'index.mdt') {
                                $result[$baseUrl] = $dir . $file;
                            } else {
                                $result[$baseUrl . substr($file, 0, -4)] = $dir . $file;
                            }
                        }
                    }
                }
            }

            return $result;
        }

        return [];
    }
}