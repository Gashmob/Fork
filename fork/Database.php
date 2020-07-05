<?php


abstract class Database
{
    /**
     * @var mysqli
     */
    private static $connection;

    public static function connect()
    {
        self::$connection = mysqli_connect('localhost', 'root', '', '');

        mysqli_set_charset(self::$connection, 'utf-8');
    }

    /**
     * @return mysqli
     */
    public static function getConnection(): mysqli
    {
        return self::$connection;
    }
}