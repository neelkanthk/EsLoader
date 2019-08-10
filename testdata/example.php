<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use Neelkanthk\EsLoader\Core\EsLoader;

$filePath = __DIR__ . "/data.json";
$config = include_once __DIR__ . '/config.php';
EsLoader::load($filePath, $config);
