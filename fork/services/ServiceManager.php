<?php

namespace Gashmob\Fork\services;

use Gashmob\YamlEditor\Yaml;

abstract class ServiceManager
{
    const SERVICES_CONFIG_FILE = __DIR__ . '/../../config/services.yml';

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
     * - Services from configuration
     *
     * @return void
     */
    public static function initialize()
    {
        // Base services
        self::$services[Router::class] = new Router();

        // Additional services
        if (file_exists(self::SERVICES_CONFIG_FILE)) {
            $config = Yaml::parseFile(self::SERVICES_CONFIG_FILE);
            if (isset($config['services'])) {
                foreach ($config['services'] as $service) {
                    self::$services[$service] = new $service();
                }
            }
        }
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