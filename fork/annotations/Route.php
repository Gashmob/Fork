<?php


namespace Fork\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Annotation Route
 * @package Fork\Annotations
 * @Annotation
 * @Target({"METHOD"})
 */
class Route
{
    /**
     * @var string
     * @Required
     */
    public $route;

    /**
     * @var string
     */
    public $name;

    /**
     * Route constructor.
     * @param array $values
     */
    public function __construct($values)
    {
        if (isset($values['route'])) {
            $this->route = $values['route'];
        }

        if (isset($values['name'])) {
            $this->name = $values['name'];
        } else {
            $this->name = '';
        }
    }


}