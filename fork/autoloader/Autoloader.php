<?php


namespace Fork\Autoloader;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Autoloader
{
    protected static $fileExt = '.php';

    protected static $pathTop = __DIR__;

    protected static $fileIterator = null;

    public static function load($className)
    {
        $directory = new RecursiveDirectoryIterator(self::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);

        if (is_null(self::$fileIterator)) {
            self::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
        }

        $filename = $className . self::$fileExt;

        foreach (self::$fileIterator as $file) {
            if (strtolower($file->getFilename()) == strtolower($filename)) {
                if ($file->isReadable())
                    include_once $file->getPathname();
                break;
            }
        }
    }

    /**
     * @param string $fileExt
     */
    public static function setFileExt($fileExt)
    {
        self::$fileExt = $fileExt;
    }

    /**
     * @param string $pathTop
     */
    public static function setPathTop($pathTop)
    {
        self::$pathTop = $pathTop;
    }
}

Autoloader::setFileExt('.php');
spl_autoload_register('\Fork\Autoloader\Autoloader::load');