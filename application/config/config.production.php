<?php

declare(strict_types=1);

// error reporting
ini_set("display_errors", 0);
ini_set("log_errors", 1);
ini_set("error_log", "syslog");

ini_set('session.cookie_httponly', 1);

return array(
    'URL' => 'http://' . $_SERVER['HTTP_HOST'] . str_replace('public', '', dirname($_SERVER['SCRIPT_NAME'])),
    'PATH_CONTROLLER' => realpath(dirname(__FILE__) . '/../../') . '/application/controller/',
    'PATH_VIEW' => realpath(dirname(__FILE__) . '/../../') . '/application/view/',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION' => 'index',

    // database settings
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',   /// TODO: check DB_HOST for production env
    'DB_NAME' => 'confscheduler',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_PORT' => '3306',
    'DB_CHARSET' => 'utf8',

    // cookie settings
    'COOKIE_RUNTIME' => 1209600, // 2 weeks
    'COOKIE_PATH' => '/',
    'COOKIE_DOMAIN' => "",
    'COOKIE_SECURE' => false, ///TODO: true for production?
    'COOKIE_HTTP' => true,
    'SESSION_RUNTIME' => 604800, // 1 week

    // encryption settings
    'ENCRYPTION_KEY' => '960BC02975F3E7C840D191DD9ECA1846449F79255C3E59B7',
    'HMAC_SALT' => 'C8A456B6F06A485C',
);