<?php

require 'init.php';

$parser = new Parser(new Origin('mvideo'));
$span1 = '<span class="product-details-overview-specification">';
$span2 = '<\/span>';
$parser->parse("/$span1(.*?)$span2\\s*<\/td>\\s*<td>\\s*$span1(.*?)$span2/");


