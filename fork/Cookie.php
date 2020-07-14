<?php


abstract class Cookie
{
    /**
     * @param string $name
     * @return mixed|null
     */
    public static function get(string $name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function set(string $name, $value = "", int $expires = 0)
    {
        setcookie($name, $value, $expires);
    }
}