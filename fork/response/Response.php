<?php

namespace Fork\Response;

/**
 * Class Response
 * Send a text response for the navigator
 * @package Fork\Response
 */
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
    public function getContent()
    {
        return $this->content;
    }
}