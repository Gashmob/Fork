<?php

namespace Gashmob\Fork\services;

final class RequestService
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

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
     * @var string
     */
    private $baseUri;

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
        $this->baseUri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' .
            substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI']) - strlen($this->uri) - 1);
        $this->getParams = $_GET;
        unset($this->getParams['page']);
        $this->postParams = $_POST;
    }

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
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