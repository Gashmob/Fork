<?php


namespace Fork\Autoloader;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class Autoloader
 * @package Fork\Autoloader
 * @see https://github.com/Gashmob/Autoload
 */
class Autoloader
{
    protected static $fileExt = '.php';

    protected static $pathTop;

    protected static $fileIterator = null;

    public static function load($className)
    {
        $directory = new RecursiveDirectoryIterator(self::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);

        if (is_null(self::$fileIterator)) {
            self::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
        }


        $f = explode('\\', $className);
        $filename = end($f) . self::$fileExt;
        foreach (self::$fileIterator as $file) {
            if (strtolower($file->getFileName()) == strtolower($filename)) {
                if ($file->isReadable())
                    include_once $file->getPathName();
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