<?php

require 'init.php';

$parser = new Parser(new Origin(Origin::OZON));
$parser
    ->addMap('Диаметр динамика, мм', 'diameter', function ($text) {
        return intval($text);
    })
    ->addMap('Коннектор', 'connector')
    ->addMap('Количество излучателей в каждом наушнике', 'dinamics')
    ->setDescrPattern('/<div class="eItemDescription_text">(.*?)<\/div>/')
    ->setNamePattern('/<h1.*?>(.+?)(?: наушники|<)/')
    ->setCharset('windows-1251');

$parser->parse('/<div class="eItemProperties_name(?:Background)?">\s*?([^<>]*?)\s*?<\/div>\s*?<div class="eItemProperties_text(?:inner)?">\s*?([^<>]*?)\s*?<\/div>/');


