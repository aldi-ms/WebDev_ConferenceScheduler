<?php

declare(strict_types=1);

class Environment
{
    /**
     * @return string the application environment
     */
    public static function get() : string
    {
        return (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : "development");
    }
}