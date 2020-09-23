<?php


namespace Fork\Controller;


use Exception;
use Fork\Response\RedirectResponse;
use Fork\Response\Response;
use Fork\Twig\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use YamlEditor\YamlArray;
use YamlEditor\YamlParser;

abstract class AbstractController
{
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
     * @param array $args
     * @return Response
     */
    public function render($template, $args = [])
    {
        try {
            return new Response((new Twig())->getTwig()->render($template, $args));
        } catch (LoaderError $e) {
            try {
                return new Response((new Twig())->getTwig()->render('errors/exception.html.twig', ['exception' => $e]));
            } catch (Exception $er) {
                die($er);
            }
        } catch (Exception $e) {
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