<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//Load classes
require __DIR__ . '/vendor/autoload.php';

use Neelkanthk\EsLoader\Core\EsLoader;

$app = new EsLoader();
$fileName = 'data.json';
$app->load(__DIR__ . "/testdata/$fileName");
