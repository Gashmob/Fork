<?php


namespace Fork\Request;


class Request
{
    /**
     * @var array
     */
    private $get;

    /**
     * @var array
     */
    private $post;

    /**
     * @var string
     */
    private $route;

    /**
     * Request constructor.
     * @param array $get
     * @param array $post
     * @param string $route
     */
    public function __construct(array $get, array $post, string $route)
    {
        $this->get = $get;
        $this->post = $post;
        $this->route = $route;
    }

    /**
     * Return the route request
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Return the post or get related variable
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if (isset($this->get[$name])) {
            return $this->get[$name];
        } else if (isset($this->post[$name])) {
            return $this->post[$name];
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function postArray()
    {
        return $this->post;
    }
}