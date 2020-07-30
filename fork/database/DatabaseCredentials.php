<?php


namespace Fork\Database;


/**
 * Class DatabaseCredentials
 * The credentials for connect to the database
 * @package Fork\Database
 */
class DatabaseCredentials
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var int
     */
    private $port;

    /**
     * DatabaseCredentials constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $dbName
     * @param int $port
     */
    public function __construct($host, $user, $password, $dbName, $port = 3306)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbName = $dbName;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }
}