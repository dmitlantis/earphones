<?php

class Prop extends AR {

    const MODEL_ID = 4;

    public $id;
    public $name;

    public static function table(): string
    {
        return 'props';
    }

    public static function getCached(int $id) {
        static $cache;
        if (!$cache) {
            $cache = static::fetchAll();
        }
        return $cache[$id] ?? null;
    }

}