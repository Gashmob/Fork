<?php

include_once './fork/includes.php';
include_once './src/Controller/HomeController.php';

$router = [
    '/' => [
        'name' => 'home',
        'controller' => (new HomeController())->homepage(),
    ],
];

$kernel = new Kernel($router);

$kernel->handle(isset($_GET['page']) ? $_GET['page'] : '/');