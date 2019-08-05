<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Core\Connector;

abstract class AbstractLoader
{

    public function __construct()
    {
        $this->elasticsearch = Connector::connection();
        $params = [
            'index' => config("index"),
            'body' => [
                'settings' => config("settings"),
                'mappings' => config("mappings")
            ]
        ];
        // Create the index if not exist
        if (!$this->elasticsearch->indices()->exists(['index' => config("index")])) {
            $this->elasticsearch->indices()->create($params);
        }
    }

}
