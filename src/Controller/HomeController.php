<?php

namespace Controller;

use Fork\Response\TemplateResponse;

class HomeController
{
    public function homepage()
    {
        return new TemplateResponse('view/home/homepage.php');
    }
}