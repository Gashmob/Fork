<?php

namespace Fork\Yaml_Editor\Exceptions;

use Exception;

class NotYamlFileException extends Exception
{
    public function __construct()
    {
        parent::__construct('Le fichier n\'est pas un .yml');
    }
}