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
    public static function load(string $filePath, array $config)
    {
        $extension = Helper::getFileExtension($filePath);
        $loader = NULL;
        switch ($extension) {
            case "json" :
                $loader = new JsonLoader($filePath, $config);
                break;
            case "csv" :
                $loader = new CsvLoader($filePath, $config);
                break;
            case "xml" :
                $loader = new XmlLoader($filePath, $config);
                break;
        }
        if (!is_null($loader)) {
            $loader->index();
        }
    }

}
