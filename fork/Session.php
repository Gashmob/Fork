<?php

namespace Fork;

/**
 * Class Session
 * Allow to set and get session variable easily
 * You can clear the session with close() method
 * @package Fork
 */
abstract class Session
{
    public static function start()
    {
        session_start();
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public static function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function close()
    {
        session_destroy();
    }
}