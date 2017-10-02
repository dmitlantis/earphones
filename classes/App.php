<?php

class App
{
    private static $instance;
    private $db;

    public function __construct()
    {
        $this->db = pg_connect('dbname=earphones password=123v321');
    }

    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public static function DB()
    {
        return self::getInstance()->getDb();
    }

}