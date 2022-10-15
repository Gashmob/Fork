<?php

namespace Gashmob\Fork\services;

final class CookieService
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
    public function set(string $name,
                        string $value,
                        int    $expire = 0,
                        string $path = '/',
                        string $domain = null,
                        bool   $secure = false,
                        bool   $httpOnly = false): CookieService
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

        return $this;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        return $_COOKIE[$name] ?? $default;
    }
}