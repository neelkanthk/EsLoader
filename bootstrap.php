<?php

//Load classes
require __DIR__ . '/vendor/autoload.php';
//Load helpers
require_once __DIR__ . '/app/helpers.php';

//Set debugging
if (config("debugging")) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
/** Mac OS settings * */
//If your CSV document was created or is read on a Macintosh computer, 
//add the following lines before using the library to help PHP detect line ending in Mac OS X
if (!ini_get("auto_detect_line_endings")) {
    //ini_set("auto_detect_line_endings", '1');
}

/** Mac OS settings * */
use Neelkanthk\EsLoader\Core\EsLoader;

$app = new EsLoader();
$fileName = "data.json";
$app->load(__DIR__ . "/app/$fileName");
