<?php

namespace Gashmob\Fork\services;

class CookieService
{

    public function __construct()
    {
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string|null $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return CookieService
     */
    public function set($name, $value, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

        return $this;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }
}