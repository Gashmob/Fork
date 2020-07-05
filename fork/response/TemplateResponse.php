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
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }
}