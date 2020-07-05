<?php


class Response
{
    /**
     * @var string
     */
    private $content;

    /**
     * Response constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}