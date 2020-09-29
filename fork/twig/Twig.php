<?php


namespace Fork\Twig;


use Fork\Kernel\Kernel;
use Fork\Kernel\Router;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use YamlEditor\Exceptions\PathNotFoundException;
use YamlEditor\YamlFile;

class Twig
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * Twig constructor.
     */
    public function __construct()
    {
        $config = (new YamlFile('config/config.yml'))->getYamlArray();
        try {
            $dev = $config->get('mod');
        } catch (PathNotFoundException $e) {
            $dev = "prod";
        }

        $loader = new FilesystemLoader('view/');
        $this->twig = new Environment($loader, $dev == "dev" ? // Si on est en dev, on ne génere pas de cache, le cache est généré en prod
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

        $this->twig->addExtension(new DebugExtension());
        $this->addFunctions();
    }

    private function addFunctions()
    {
        $this->twig->addFunction(new TwigFunction('asset', function ($asset) { // Fonction asset(string), renvoie le chemin depuis la racine vers l'asset (la resource)
            return Kernel::$relativeUri . '/resources/' . $asset;
        }));
        $this->twig->addFunction(new TwigFunction('route', function ($routeName, $args = []) { // Fonction route(string, array), renvoie l'url relative pour la route demandée
            $router = new Router();
            return Kernel::$relativeUri . $router->getRoute($routeName, $args);
        }));
        $this->twig->addFunction(new TwigFunction('url', function ($routeName, $args = []) { // Fonction url(string, array), renvoie l'url absolu pour la route demandée
            $router = new Router();
            return Kernel::$absoluteUri . $router->getRoute($routeName, $args);
        }));
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}