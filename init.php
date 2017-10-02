<?php

function __autoload($classname) {
    $filename = __DIR__ . "/classes/$classname.php";
    include_once($filename);
}
