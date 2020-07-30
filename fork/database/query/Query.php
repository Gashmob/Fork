<?php


namespace Fork\Database\Query;


use Fork\Database\DatabaseConnection;
use Fork\Database\Exceptions\DatabaseNotConnectedException;
use mysqli_result;

/**
 * Class Query
 * A simple mysqli query
 * @package Fork\Database\Query
 */
class Query
{
    /**
     * @var string
     */
    private $query;

    /**
     * Query constructor.
     * Take the complete query to the database
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Execute the query
     */
    public function execute()
    {
        try {
            DatabaseConnection::getConnection()->query($this->query);
        } catch (DatabaseNotConnectedException $e) {
            die($e);
        }
    }

    /**
     * Return the result of the query
     * @return array|bool
     */
    public function getResult()
    {
        try {
            $result = DatabaseConnection::getConnection()->query($this->query);
        } catch (DatabaseNotConnectedException $e) {
            die($e);
        }

        if ($result instanceof mysqli_result) {
            $tab = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $tab[] = $row;
            }

            return $tab;
        } else { // is bool
            return $result;
        }
    }

    /**
     * Return the first row of query result
     * @return array|bool|null
     */
    public function getOneOrNullResult()
    {
        try {
            $result = DatabaseConnection::getConnection()->query($this->query);
        } catch (DatabaseNotConnectedException $e) {
            die($e);
        }

        if ($result instanceof mysqli_result) {
            return $result->fetch_assoc();
        } else { // is bool
            return $result;
        }
    }
}