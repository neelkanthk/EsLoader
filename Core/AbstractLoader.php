<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Core\Elasticsearch\Connector;
use Neelkanthk\EsLoader\Core\Helper;

abstract class AbstractLoader
{

    public function __construct()
    {
        $this->elasticsearch = Connector::connection();
        $params = [
            'index' => Helper::config("index"),
            'body' => [
                'settings' => Helper::config("settings"),
                'mappings' => Helper::config("mappings")
            ]
        ];
        // Create the index if not exist
        if (!$this->elasticsearch->indices()->exists(['index' => Helper::config("index")])) {
            $this->elasticsearch->indices()->create($params);
        }
    }

}
