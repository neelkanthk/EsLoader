<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Loaders\JsonLoader;
use Neelkanthk\EsLoader\Loaders\CsvLoader;
use Neelkanthk\EsLoader\Loaders\XmlLoader;

class EsLoader
{

    public $loader;

    public function __construct()
    {
        
    }

    public function load($filePath)
    {
        $extension = get_file_extension($filePath);
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
