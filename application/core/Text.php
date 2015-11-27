<?php

declare(strict_types = 1);

class Text
{
    private static $texts;

    /**
     * Get a string from config/texts.php
     * @param $key
     * @return string
     */
    public static function get($key) : string
    {
        if (!$key) {
            return "";
        }

        if (!self::$texts) {
            self::$texts = require('../config/texts.php');
        }

        if (!array_key_exists($key, self::$texts)) {
            return "";
        }

        return self::$texts[$key];
    }
}