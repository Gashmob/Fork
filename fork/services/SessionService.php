<?php

namespace Gashmob\Fork\services;

final class SessionService
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
    public function set(string $key, $value): SessionService
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * @return SessionService
     */
    public function clear(): SessionService
    {
        session_destroy();

        return $this;
    }
}