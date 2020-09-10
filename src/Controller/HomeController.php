<?php

namespace Controller;

use Fork\Annotations\Route;
use Fork\Response\TemplateResponse;

class HomeController
{
    /**
     * @Route(route="/", name="home")
     * @return TemplateResponse
     */
    public function homepage()
    {
        return new TemplateResponse('view/home/homepage.php');
    }
}