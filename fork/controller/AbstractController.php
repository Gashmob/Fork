<?php


namespace Fork\Controller;


use Fork\response\JsonResponse;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use XMLParser\XMLParser;
use YamlEditor\Exceptions\PathNotFoundException;
use YamlEditor\YamlArray;
use YamlEditor\YamlFile;
use YamlEditor\YamlParser;

abstract class AbstractController
{
    private $twig;

    public function __construct()
    {
        $config = (new YamlFile('config/config.yml'))->getYamlArray();
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
     * @param string $text
     * @return Response
     */
    public function text(string $text)
    {
        return new Response($text);
    }

    /**
     * @param YamlArray $array
     * @return Response
     */
    public function yaml(YamlArray $array)
    {
        $a = YamlParser::toYaml($array);
        $a = str_replace(chr(10), '</br>', $a); // Replace \n
        $a = str_replace(chr(32), '&nbsp;', $a); // Replace tabulation
        return new Response($a);
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
     * @param array $args
     * @return RedirectResponse
     */
    public function redirectToRoute($routeName, array $args = [])
    {
        return new RedirectResponse($routeName, $args);
    }
}