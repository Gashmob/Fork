<?php

namespace Fork\Yaml_Editor\Exceptions;

use Exception;

class PathNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Le chemin demandé n\'existe pas');
    }
}