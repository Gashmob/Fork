<?php

use Gashmob\Fork\Router;

require_once '../vendor/autoload.php';

$router = new Router($_GET['page']);
