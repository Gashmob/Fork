<?php

namespace Controller;

use Gashmob\Fork\AbstractController;
use Gashmob\Fork\exceptions\RouteNotFoundException;
use Gashmob\Fork\responses\RedirectResponse;
use Gashmob\Fork\services\RequestService;

class MainController extends AbstractController
{
    /**
     * @param RequestService $request
     * @return RedirectResponse
     * @throws RouteNotFoundException
     */
    public function get(RequestService $request): RedirectResponse
    {
        if ($request->getLanguage() === 'fr') {
            return $this->redirect('fr');
        }

        return $this->redirect('/en');
    }
}