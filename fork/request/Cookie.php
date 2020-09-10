<?php

namespace Fork\Request;

/**
 * Class Cookie
 * Allow to set and get cookie easily
 * @package Fork
 */
abstract class Cookie
{
    /**
     * @param string $name
     * @return mixed|null
     */
    public static function get($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function set($name, $value = "", $expires = 0)
    {
        setcookie($name, $value, $expires);
    }
}