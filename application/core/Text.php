<?php

declare(strict_types = 1);

class Text
{
    private static $texts;

    public static function get($key)
    {
        if (!$key) {
            return null;
        }

        if (!self::$texts) {
            self::$texts = require('../config/texts.php');
        }

        if (!array_key_exists($key, self::$texts)) {
            return null;
        }

        return self::$texts[$key];
    }
}