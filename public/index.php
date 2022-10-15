<?php

require_once '../vendor/autoload.php';

use Gashmob\Fork\Kernel;

$kernel = new Kernel();

try {
    echo $kernel->render();
} catch (Exception $e) {
    echo $e->getMessage();
}