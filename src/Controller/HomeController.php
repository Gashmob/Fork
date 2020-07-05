<?php

include_once 'fork/includes.php';

class HomeController
{
    public function homepage()
    {
        return new TemplateResponse('view/home/homepage.php');
    }
}