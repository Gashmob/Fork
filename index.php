<?php

use Autoloader\Autoloader;
use Controller\HomeController;
use Fork\Kernel;
use Fork\Request;

include_once 'fork/autoload/Autoloader.php';

Autoloader::setPathTop(__DIR__);
spl_autoload_register('Autoloader\Autoloader::load');

$request = new Request($_GET, $_POST, isset($_GET['page']) ? $_GET['page'] : '/');

// _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.
$router = [
    '/' => [
        'name' => 'home',
        'controller' => (new HomeController())->homepage(),
    ],
];
// _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

$kernel = new Kernel($router);

$kernel->handle($request);