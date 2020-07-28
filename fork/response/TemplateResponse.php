<?php


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