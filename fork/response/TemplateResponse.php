<?php

namespace Fork\Response;

/**
 * Class TemplateResponse
 * Send a html response to the navigator via a template
 * @package Fork\Response
 */
class TemplateResponse
{
    /**
     * @var string
     */
    private $template;

    /**
     * TemplateResponse constructor.
     * @param string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}