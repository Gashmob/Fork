<?php

namespace Gashmob\Fork\services;

class RequestService
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $getParams;
    /**
     * @var array
     */
    private $postParams;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
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
    public function getLanguage()
    {
        return $this->lang;
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