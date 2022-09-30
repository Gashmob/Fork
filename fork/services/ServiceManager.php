<?php

namespace Gashmob\Fork\services;

abstract class ServiceManager
{
    /**
     * All the registered services.
     *
     * @var array
     */
    private static $services = [];

    // _.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-._.-.

    /**
     * Initialize all services
     * - Basic services of Fork
     * - Services from configuration TODO : create config file config/services.yml (need a library to parse yaml)
     *
     * @return void
     */
    public static function initialize()
    {
        self::$services[Router::class] = new Router();
    }

    /**
     * @param string $service
     * @return mixed|null
     */
    public static function getService($service)
    {
        if (isset(self::$services[$service])) {
            return self::$services[$service];
        } else {
            return null;
        }
    }
}