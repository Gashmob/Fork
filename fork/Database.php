<?php


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
    public static function query(string $query)
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
    public static function prepare(string $preparedQuery)
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
    public static function setInt(int $number)
    {
        return self::$stmt->bind_param('i', $number);
    }

    /**
     * @param float $number
     * @return bool
     */
    public static function setFloat(float $number)
    {
        return self::$stmt->bind_param('d', $number);
    }

    /**
     * @param string $str
     * @return bool
     */
    public static function setString(string $str)
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