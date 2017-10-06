<?php

require 'init.php';

$parser = new Parser(new Origin(Origin::MVIDEO));
$span1 = '<span class="product-details-overview-specification">';
$span2 = '<\/span>';
$parser->setUrl('/specification?ssb_block=descriptionTabContentBlock');
$parser->setNamePattern('/Инструкция для&nbsp([^\(<]+)[<(]/');
$parser->setDelay(1,2);
$parser->parse("/$span1(.*?)$span2\\s*<\/td>\\s*<td>\\s*$span1(.*?)$span2/");


