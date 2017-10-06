<?php

class Origin
{
    const OZON = 'ozon';
    const MVIDEO = 'mvideo';

    public $code;

    const HOSTS = [
        self::MVIDEO => 'www.mvideo.ru',
        self::OZON   => 'www.ozon.ru',
    ];

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function generateUrl(string $url = ''):string
    {
        return static::generateUrlOfCode($this->code, $url);
    }

    public static function generateUrlOfCode(string $code, string $url = '')
    {
        return static::getHttpOfCode($code) . $url;
    }

    public function getHost()
    {
        return static::getHostOfCode($this->code);
    }

    public static function getHostOfCode(string $code)
    {
        return static::HOSTS[$code];
    }

    public function getHttp()
    {
        return static::getHttpOfCode($this->code);
    }

    public static function getHttpOfCode(string $code)
    {
        return 'http://' . static::getHostOfCode($code);
    }

}