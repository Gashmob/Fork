<?php

namespace Gashmob\Fork\http;

class Request
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
}