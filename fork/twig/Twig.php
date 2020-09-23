<?php


namespace Fork\Twig;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
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
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}