<?php


namespace Fork\Annotations;


use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;

class RouteAnnotationReader
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct()
    {
        $this->reader = new AnnotationReader();
    }

    /**
     * @param string|object $object
     * @return array
     * @throws ReflectionException
     */
    public function getRoutes($object)
    {
        $reflection = new ReflectionClass(is_string($object) ? $object : get_class($object));
        $methods = [];

        foreach ($reflection->getMethods() as $method) {
            $annotation = $this->reader->getMethodAnnotation($method, Route::class);
            if ($annotation != null && $annotation instanceof Route) {
                $methods[] = [
                    'method' => $method,
                    'route' => $annotation->route,
                    'routeName' => $annotation->name
                ];
            }
        }

        return $methods;
    }
}