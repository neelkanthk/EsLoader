<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Loaders\JsonListener;
use JsonStreamingParser\Parser as JsonParser;
use Neelkanthk\EsLoader\Core\Helper;
use Exception;

class JsonLoader extends AbstractLoader
{

    protected $json;
    protected $listener;

    public function __construct(string $filePath, array $config)
    {
        $this->json = fopen($filePath, 'rb');
        $this->listener = new JsonListener();
        parent::__construct($config);
    }

    /**
     * Index data into Elasticsearch
     */
    public function index()
    {
        try {
            $parser = new JsonParser($this->json, $this->listener);
            $parser->parse();
            $records = $this->listener->getJson();
            if (count($records) > 0) {
                $iteration = 1;
                foreach ($records as $record) {
                    if (!is_null($this->config["doc_id_key"])) {
                        $params['body'][] = [
                            'index' => [
                                '_index' => $this->config["index"],
                                '_type' => '_doc',
                                '_id' => $record["id"]
                            ]
                        ];
                    } else {
                        $params['body'][] = [
                            'index' => [
                                '_index' => $this->config["index"],
                                '_type' => '_doc'
                            ]
                        ];
                    }

                    $params['body'][] = $record;
                    // Every 100 elements stop and send the bulk request
                    if ($iteration % 100 == 0) {
                        $this->bulkLoad($params);
                        $params = ['body' => []];
                    }
                    $iteration++;
                }
                // Send the last batch if it exists
                if (!empty($params['body'])) {
                    $this->bulkLoad($params);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
