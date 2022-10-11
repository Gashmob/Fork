<?php

namespace Gashmob\Fork\services;

class SessionService
{
    public function __construct()
    {
        session_start();
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $key
     * @param mixed $value
     * @return SessionService
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * @return SessionService
     */
    public function clear()
    {
        session_destroy();

        return $this;
    }
}