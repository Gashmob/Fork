<?php

require_once '../vendor/autoload.php';

use Gashmob\Fork\services\Router;
use Gashmob\Fork\services\ServiceManager;

ServiceManager::initialize();

var_dump(ServiceManager::getService(Router::class)->dump());