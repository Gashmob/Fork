<?php


namespace Fork\Database\Query;


use Fork\Database\DatabaseConnection;
use Fork\Database\Exceptions\DatabaseNotConnectedException;
use mysqli_result;

class Query
{
    /**
     * @var string
     */
    private $query;

    /**
     * Query constructor.
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
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