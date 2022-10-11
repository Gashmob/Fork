<?php

namespace Gashmob\Fork\services;

class RequestService
{
    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $uri;

    /**
     * @var array
     */
    public $getParams;
    /**
     * @var array
     */
    public $postParams;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = isset($_GET['page']) ? '/' . $_GET['page'] : '/';
        $this->getParams = $_GET;
        unset($this->getParams['page']);
        $this->postParams = $_POST;
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (isset($this->getParams[$key])) {
            return $this->getParams[$key];
        }

        if (isset($this->postParams[$key])) {
            return $this->postParams[$key];
        }

        return $default;
    }
}