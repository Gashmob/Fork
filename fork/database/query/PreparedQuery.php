<?php


namespace Fork\Database\Query;


use Fork\Database\DatabaseConnection;
use Fork\Database\Exceptions\DatabaseNotConnectedException;
use mysqli_result;
use mysqli_stmt;

/**
 * Class PreparedQuery
 * Allow to prepare query
 * @package Fork\Database\Query
 */
class PreparedQuery
{
    /**
     * @var mysqli_stmt
     */
    private $stmt;

    /**
     * PreparedQuery constructor.
     * Take a query to prepare -> SELECT * FROM user WHERE id = ?
     * @param string $query
     */
    public function __construct($query)
    {
        try {
            $this->stmt = DatabaseConnection::getConnection()->prepare($query);
        } catch (DatabaseNotConnectedException $e) {
            die($e);
        }
    }

    /**
     * Execute the query
     */
    public function execute()
    {
        $this->stmt->execute();
    }

    /**
     * Return the complete result of query
     * @return array|false
     */
    public function getResult()
    {
        if ($this->stmt->execute()) {
            $result = $this->stmt->get_result();

            if ($result instanceof mysqli_result) {
                $tab = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $tab[] = $row;
                }

                return $tab;
            }
        }
        return false;
    }

    /**
     * Return the first row of query result
     * @return array|null
     */
    public function getOneOrNullResult()
    {
        if ($this->stmt->execute()) {
            $result = $this->stmt->get_result();

            if ($result instanceof mysqli_result) {
                return $result->fetch_assoc();
            }
        }
        return null;
    }

    /**
     * Bind an integer parameter with $number
     * @param int $number
     * @return PreparedQuery
     */
    public function setInt($number)
    {
        $this->stmt->bind_param('i', $number);
        return $this;
    }

    /**
     * Bind a float parameter with $number
     * @param float $number
     * @return PreparedQuery
     */
    public function setFloat($number)
    {
        $this->stmt->bind_param('d', $number);
        return $this;
    }

    /**
     * Bind a string parameter with $str
     * @param string $str
     * @return PreparedQuery
     */
    public function setString($str)
    {
        $this->stmt->bind_param('s', $str);
        return $this;
    }
}