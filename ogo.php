<?php

require 'init.php';

$parser = new Parser(new Origin(Origin::OGO));
$parser
    ->addMap('Тип разъема', 'connector')
    ->addMap('Диаметр динамиков (мм)', 'diameter')
    ->addMap('Количество динамиков в чаше', 'dinamics')
    ->setNamePattern('/<h1[^>]*?>\s*?Гарнитура(.+?)\s*?</')
    ->setDescrPattern('/<div class="description[^>]*?>\s*?Гарнитура(.+?)\s*?<\/div/');

$parser->parse('/<td class="col1">\s*?([^<>]*?)\s*?<\/td>\s*?<td class="black col2">\s*?([^<>]*?)\s*?<\/td>/');


