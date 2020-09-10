<?php

namespace Controller;

use Fork\Annotations\Route;
use Fork\Controller\AbstractController;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;

class HomeController extends AbstractController
{
    /**
     * @Route(route="/", name="home")
     * @return Response
     */
    public function homepage()
    {
        return $this->render('home/homepage.html.twig');
    }

    /**
     * @Route(route="/redirect", name="redirect")
     * @return RedirectResponse
     */
    public function redirect()
    {
        return $this->redirectToRoute('home');
    }
}