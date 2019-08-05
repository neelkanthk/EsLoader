<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Loaders\JsonLoader;
use Neelkanthk\EsLoader\Loaders\CsvLoader;
use Neelkanthk\EsLoader\Loaders\XmlLoader;
use Neelkanthk\EsLoader\Core\Helper;

class EsLoader
{

    public $loader;

    public function __construct()
    {
        /**
         * If your CSV document was created or is read on a Macintosh computer,
         * add the following lines before using the library to help PHP detect line ending in Mac OS X
         */
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
    }

    public function load($filePath)
    {
        $extension = Helper::getFileExtension($filePath);
        switch ($extension) {
            case "json" :
                $this->loader = new JsonLoader($filePath);
                $this->loader->index();
                break;
            case "csv" :
                $this->loader = new CsvLoader($filePath);
                $this->loader->index();
                break;
            case "xml" :
                $this->loader = new XmlLoader($filePath);
                $this->loader->index();
                break;
        }
    }

}
