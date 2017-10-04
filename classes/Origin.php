<?php

class Origin
{
    public $code;

    const HOSTS = [
        'mvideo' => 'www.mvideo.ru',
        'ozon'   => 'www.ozon.ru',
    ];

    const URLS = [
        'mvideo' => '/specification?ssb_block=descriptionTabContentBlock',
    ];

    const CHARSET = [
        'ozon' => 'windows-1251',
    ];

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function generateUrl(string $url = ''):string
    {
        return $this->getHttp(). $url . (static::URLS[$this->code] ?? '');
    }

    public function getHost()
    {
        return static::HOSTS[$this->code];
    }

    public function getHttp()
    {
        return 'http://' . $this->getHost() . '/';
    }

    public function convert(string $string)
    {
        return isset(static::CHARSET[$this->code]) ? mb_convert_encoding($string, 'utf-8', static::CHARSET[$this->code]) : $string;
    }



}