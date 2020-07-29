<?php

use Controller\HomeController;
use Fork\Autoloader\Autoloader;
use Fork\Kernel;

include_once 'fork/autoloader/Autoloader.php';

Autoloader::setPathTop(__DIR__);
spl_autoload_register('\Fork\Autoloader\Autoloader::load');

$router = [
    '/' => [
        'name' => 'home',
        'controller' => (new HomeController())->homepage(),
    ],
];

$kernel = new Kernel($router);

$kernel->handle(isset($_GET['page']) ? $_GET['page'] : '/');