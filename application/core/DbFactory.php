<?php

declare(strict_types = 1);

class DbFactory
{
    private static $factory;
    private $database;

    /**
     * Get singleton instance of DbFactory
     * @return DbFactory
     */
    public static function getFactory() : DbFactory
    {
        if (!self::$factory) {
            self::$factory = new DbFactory();
        }
        return self::$factory;
    }

    /**
     * Construct database connection if necessary and return it.
     * @return PDO
     */
    public function getConnection() : PDO
    {
        if (!$this->database) {
            $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
            $this->database = new PDO(
                Config::get('DB_TYPE') . ':host=' . Config::get('DB_HOST') . ';dbname=' .
                Config::get('DB_NAME') . ';port=' . Config::get('DB_PORT') . ';charset=' . Config::get('DB_CHARSET'),
                Config::get('DB_USER'), Config::get('DB_PASS'), $options
            );
        }
        return $this->database;
    }
}