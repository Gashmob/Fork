<?php

session_start();

include_once './fork/includes.php';

$router = [
];

$kernel = new Kernel($router);

$kernel->handle(isset($_GET['page']) ? $_GET['page'] : '/');