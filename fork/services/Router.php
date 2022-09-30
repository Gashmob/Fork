<?php

namespace Gashmob\Fork\services;

class Router
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

    public function dump()
    {
        return $this->routes;
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $dir
     * @return array
     */
    private function fetchRoutes($dir = self::PAGES_DIR)
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
                                $result[] = $baseUrl;
                            } else {
                                $result[] = $baseUrl . substr($file, 0, -4);
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