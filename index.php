<?php

use Autoloader\Autoloader;
use Fork\Kernel\Kernel;
use Fork\Request\Request;

include_once 'fork/autoload/Autoloader.php';

Autoloader::setPathTop(__DIR__);
spl_autoload_register('Autoloader\Autoloader::load');

$request = new Request($_GET, $_POST, isset($_GET['page']) ? '/' . $_GET['page'] : '/');

$kernel = new Kernel($request);

try {
    $kernel->handle();
} catch (ReflectionException $e) {
    die($e);
} catch (Exception $e) {
    die($e);
}