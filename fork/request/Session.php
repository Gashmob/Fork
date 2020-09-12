<?php

namespace Fork\Request;

/**
 * Class Session
 * Allow to set and get session variable easily
 * You can clear the session with close() method
 * @package Fork
 */
class Session
{
    public function __construct()
    {
        $this->start();
    }

    private function start()
    {
        session_start();
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function close()
    {
        session_destroy();
    }
}