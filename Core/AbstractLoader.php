<?php

namespace Neelkanthk\EsLoader\Core;

use Neelkanthk\EsLoader\Core\Elasticsearch\Connector;
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
            throw $ex;
        }
    }

    /**
     * Prepare request body for bulk index request
     * @param array $params
     * @param array $data
     * @param array $config
     * @return array
     */
    public function prepareBulkIndexRequest(&$params, $data, $config)
    {
        if (!is_null($config["doc_id_key"])) {
            $params['body'][] = [
                'index' => [
                    '_index' => $config["index"],
                    '_type' => "_doc",
                    '_id' => $data[$config["doc_id_key"]]
                ]
            ];
        } else {
            $params['body'][] = [
                'index' => [
                    '_index' => $config["index"],
                    '_type' => "_doc"
                ]
            ];
        }
        $params['body'][] = $data;
        return $params;
    }

    /**
     * Function to index bulk documents
     * 
     * @param type $params
     */
    public function bulkIndex($params)
    {
        if (!empty($params["body"])) {
            $this->elasticsearch->bulk($params);
        }
    }

}
