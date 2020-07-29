<?php


namespace Fork\Database\Query;


use Fork\Database\DatabaseConnection;
use Fork\Database\Exceptions\DatabaseNotConnectedException;
use mysqli_result;
use mysqli_stmt;

class PreparedQuery
{
    /**
     * @var mysqli_stmt
     */
    private $stmt;

    /**
     * PreparedQuery constructor.
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
     * @param int $number
     * @return PreparedQuery
     */
    public function setInt($number)
    {
        $this->stmt->bind_param('i', $number);
        return $this;
    }

    /**
     * @param float $number
     * @return PreparedQuery
     */
    public function setFloat($number)
    {
        $this->stmt->bind_param('d', $number);
        return $this;
    }

    /**
     * @param string $str
     * @return PreparedQuery
     */
    public function setString($str)
    {
        $this->stmt->bind_param('s', $str);
        return $this;
    }
}