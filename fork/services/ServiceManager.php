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
        self::$services[RouterService::class] = new RouterService();
        self::$services[RequestService::class] = new RequestService();

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
     * Test if the service exists.
     *
     * @param string $service
     * @return bool
     */
    public static function hasService($service)
    {
        return isset(self::$services[$service]);
    }

    /**
     * Return the service instance.
     * If the service does not exist, return null.
     *
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

    /**
     * Return all existing services.
     *
     * @return string[]
     */
    public static function listServices()
    {
        return array_keys(self::$services);
    }
}