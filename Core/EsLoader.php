<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Loaders\JsonLoader;
use Neelkanthk\EsLoader\Loaders\CsvLoader;
use Neelkanthk\EsLoader\Loaders\XmlLoader;
use Neelkanthk\EsLoader\Core\Helper;

class EsLoader
{

    /**
     * Select the Loader based on the file extension
     * @param type $filePath
     */
    public static function load(string $filePath, array $esConfig)
    {
        $extension = Helper::getFileExtension($filePath);
        $loader = NULL;
        switch ($extension) {
            case "json" :
                $loader = new JsonLoader($filePath, $esConfig);
                break;
            case "csv" :
                $loader = new CsvLoader($filePath, $esConfig);
                break;
            case "xml" :
                $loader = new XmlLoader($filePath, $esConfig);
                break;
        }
        if (!is_null($loader)) {
            $loader->index();
        }
    }

}
