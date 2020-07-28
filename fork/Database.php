<?php

namespace Fork;

use mysqli;
use mysqli_result;
use mysqli_stmt;

abstract class Database
{
    /**
     * @var mysqli
     */
    private static $mysqli;

    /**
     * @var mysqli_stmt
     */
    private static $stmt;

    public static function connect()
    {
        if (self::$mysqli == null) {
            self::$mysqli = new mysqli('localhost', 'root', '', '');
        }
    }

    /**
     * @param string $query
     * @return bool|mysqli_result
     */
    public static function query($query)
    {
        if (self::$mysqli == null) {
            self::connect();
        }

        return self::$mysqli->query($query);
    }

    /**
     * @param string $preparedQuery
     * @return bool
     */
    public static function prepare($preparedQuery)
    {
        if (self::$stmt = self::$mysqli->prepare($preparedQuery)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $number
     * @return bool
     */
    public static function setInt($number)
    {
        return self::$stmt->bind_param('i', $number);
    }

    /**
     * @param float $number
     * @return bool
     */
    public static function setFloat($number)
    {
        return self::$stmt->bind_param('d', $number);
    }

    /**
     * @param string $str
     * @return bool
     */
    public static function setString($str)
    {
        return self::$stmt->bind_param('s', $str);
    }

    /**
     * @return bool
     */
    public static function getResult()
    {
        if (self::$mysqli == null) {
            self::connect();
        }

        if (!self::$stmt == null) {
            return self::$stmt->execute();
        }

        return false;
    }
}