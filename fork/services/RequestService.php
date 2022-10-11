<?php

namespace Gashmob\Fork\services;

use Gashmob\Fork\http\Request;

class RequestService
{
    /**
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->request->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->request->uri;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (isset($this->request->getParams[$key])) {
            return $this->request->getParams[$key];
        }

        if (isset($this->request->postParams[$key])) {
            return $this->request->postParams[$key];
        }

        return $default;
    }
}