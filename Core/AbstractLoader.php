<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Core\Elasticsearch\Connector;
use Neelkanthk\EsLoader\Core\Helper;
use Exception;

abstract class AbstractLoader
{

    public $elasticsearch;
    public $config;

    /**
     * Initialize Elasticsearch
     */
    public function __construct($config)
    {
        try {

            $this->elasticsearch = Connector::connection($config);
            $params = [
                'index' => $config["index"],
                'body' => [
                    'settings' => $config["settings"],
                    'mappings' => $config["mappings"]
                ]
            ];
            // Create the index if not exist
            if (!$this->elasticsearch->indices()->exists(['index' => $params["index"]])) {
                $this->elasticsearch->indices()->create($params);
            }
            $this->config = $config;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Function to index bulk documents
     * 
     * @param type $params
     */
    public function bulkLoad($params)
    {
        if (!empty($params["body"])) {
            $this->elasticsearch->bulk($params);
        }
    }

    /**
     * Function to index single document
     * 
     * @param type $params
     */
    public function singleLoad($params)
    {
        if (!empty($params["body"])) {
            $this->elasticsearch->index($params);
        }
    }

}
