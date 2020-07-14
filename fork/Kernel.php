<?php


class Kernel
{
    /**
     * @var array
     */
    private $router;

    /**
     * Kernel constructor.
     * @param array $router
     */
    public function __construct(array $router)
    {
        $this->router = $router;

        Database::connect();
        Session::start();
    }


    /**
     * @param string $request
     */
    public function handle(string $request)
    {
        if (isset($this->router[$request])) {
            if (isset($this->router[$request]['controller'])) {
                $control = $this->router[$request]['controller'];

                if ($control instanceof Response) {
                    echo $control->getContent();
                } elseif ($control instanceof TemplateResponse) {
                    include_once "{$control->getTemplate()}";
                } elseif ($control instanceof RedirectResponse) {
                    $routeName = $control->getRouteName();

                    $redirect = '';
                    foreach ($this->router as $route => $properties) {
                        if (isset($properties['name'])) {
                            if ($properties['name'] == $routeName) {
                                $redirect = $route;
                            }
                        }
                    }

                    $this->handle($redirect);
                }
            }
        }
    }
}