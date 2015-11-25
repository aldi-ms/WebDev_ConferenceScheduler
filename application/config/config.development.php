<?php

declare(strict_types=1);

/**
 * Configuration for development environment.
 */

// error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

ini_set('session.cookie_httponly', 1);

return array(
    'URL' => 'http://127.0.0.1:8080/',
    'PATH_CONTROLLER' => realpath(dirname(__FILE__) . '/../../') . '/application/controller/',
    'PATH_VIEW' => realpath(dirname(__FILE__) . '/../../') . '/application/view/',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION' => 'index',

    // database settings
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'ConfScheduler',
    'DB_USER' => 'root',
    'DB_PASS' => '123456',
    'DB_PORT' => '3306',
    'DB_CHARSET' => 'utf8',

    // cookie settings
    'COOKIE_RUNTIME' => 1209600, // 2 weeks
    'COOKIE_PATH' => '/',
    'COOKIE_DOMAIN' => "",
    'COOKIE_SECURE' => false,
    'COOKIE_HTTP' => true,
    'SESSION_RUNTIME' => 604800, // 1 week
);