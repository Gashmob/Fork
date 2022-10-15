<?php

namespace Gashmob\Fork\responses;

abstract class AbstractResponse
{
    /**
     * @return string
     */
    abstract public function handle(): string;
}