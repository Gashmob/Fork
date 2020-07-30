<?php


namespace Fork\Database;


use Fork\Database\Exceptions\ConnectionFailedException;
use Fork\Database\Exceptions\DatabaseNotConnectedException;
use mysqli;

/**
 * Class DatabaseConnection
 * Connection to the database
 * @package Fork\Database
 */
abstract class DatabaseConnection
{
    /**
     * @var mysqli
     */
    private static $connection;

    /**
     * If not connected, connect to the database
     * Throw an exception if the connection failed
     * @param DatabaseCredentials $databaseCredentials
     * @throws ConnectionFailedException
     */
    public static function connect(DatabaseCredentials $databaseCredentials)
    {
        if (self::$connection == null) {
            self::$connection = new mysqli(
                $databaseCredentials->getHost(),
                $databaseCredentials->getUser(),
                $databaseCredentials->getPassword(),
                $databaseCredentials->getDbName(),
                $databaseCredentials->getPort()
            );

            if (mysqli_connect_errno()) {
                throw new ConnectionFailedException();
            }
        }
    }

    /**
     * Return the connection to the database
     * Throw an exception if we aren't connected
     * @return mysqli
     * @throws DatabaseNotConnectedException
     */
    public static function getConnection()
    {
        if (self::$connection == null) {
            throw new DatabaseNotConnectedException();
        }
        return self::$connection;
    }
}