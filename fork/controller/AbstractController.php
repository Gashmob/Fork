<?php


namespace Fork\Controller;


use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use YamlEditor\Exceptions\PathNotFoundException;
use YamlEditor\YamlArray;
use YamlEditor\YamlFile;

abstract class AbstractController
{
    private $twig;

    public function __construct()
    {
        $config = new YamlArray(new YamlFile('config/config.yml'));
        try {
            $dev = $config->get('mod');
        } catch (PathNotFoundException $e) {
            $dev = "prod";
        }

        $loader = new FilesystemLoader('view/');
        $this->twig = new Environment($loader, $dev == "dev" ?
            [
                'debug' => true,
                'cache' => false,
                'auto_reload' => true
            ]
            :
            [
                'debug' => false,
                'cache' => 'cache/templates',
                'auto_reload' => false
            ]);
    }

    /**
     * @param $template
     * @param $args
     * @return Response
     */
    public function render($template, $args = [])
    {
        try {
            return new Response($this->twig->render($template, $args));
        } catch (LoaderError $e) {
            die($e);
        } catch (RuntimeError $e) {
            die($e);
        } catch (SyntaxError $e) {
            die($e);
        }
    }

    /**
     * @param $routeName
     * @return RedirectResponse
     */
    public function redirectToRoute($routeName)
    {
        return new RedirectResponse($routeName);
    }
}