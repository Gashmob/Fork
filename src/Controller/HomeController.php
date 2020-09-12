<?php

namespace Controller;

use Fork\Annotations\Route;
use Fork\Controller\AbstractController;
use Fork\Request\Session;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;

class HomeController extends AbstractController
{
    /**
     * @var Session
     */
    private $session;

    /**
     * HomeController constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        parent::__construct();
        $this->session = $session;
    }


    /**
     * @Route(route="/", name="home")
     * @return Response
     */
    public function homepage()
    {
        return $this->render('home/homepage.html.twig');
    }

    /**
     * @Route(route="/redirect/{route}", name="redirect")
     * @param string $route
     * @return RedirectResponse
     */
    public function redirect(string $route)
    {
        return $this->redirectToRoute($route);
    }
}