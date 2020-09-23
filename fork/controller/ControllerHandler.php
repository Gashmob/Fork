<?php


namespace Fork\Controller;


use Exception;

class ControllerHandler
{
    private static $namespace = 'Controller\\';
    private static $path = 'src/Controller/';

    /**
     * @return string[]
     * @throws Exception
     */
    public function getControllers()
    {
        $this->includesControllers(self::$path);
        $allClasses = get_declared_classes();

        $controllers = [];
        foreach ($allClasses as $class) {
            if ($this->isCorrectNamespace($class)) $controllers[] = $class;
        }

        return $controllers;
    }

    /**
     * @param string $path
     */
    private function includesControllers($path)
    {
        $dir = opendir($path);
        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..') {
                if (is_dir($path.$file)) {
                    $this->includesControllers($path.$file.'/');
                } else {
                    include_once ($path.$file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * @param string $class
     * @return bool
     */
    private function isCorrectNamespace($class)
    {
        return $this->startWith($class, self::$namespace);
    }

    /**
     * @param string $string
     * @param string $startString
     * @return bool
     */
    private function startWith($string, $startString)
    {
        $len = strlen($startString);
        return substr($string, 0, $len) === $startString;
    }
}