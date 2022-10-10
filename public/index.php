<?php

require_once '../vendor/autoload.php';

use Gashmob\Fork\services\RouterService;
use Gashmob\Fork\services\ServiceManager;

ServiceManager::initialize();

var_dump(ServiceManager::getService(RouterService::class)->dump());