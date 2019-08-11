<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Loaders\JsonListener;
use JsonStreamingParser\Parser as JsonParser;
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
            $batchSize = $this->config['batch_size'];
            $parser = new JsonParser($this->json, $this->listener);
            $parser->parse();
            $records = $this->listener->getJson();
            $params = ['body' => []];
            $iteration = 1;
            if (count($records) > 0) {
                foreach ($records as $record) {
                    $this->prepareBulkIndexRequest($params, $record, $this->config);
                    // Every $batchSize elements stop and send the bulk request
                    if ($iteration % $batchSize == 0) {
                        $this->bulkIndex($params);
                        $params = ['body' => []];
                    }
                    $iteration++;
                }
                // Send the last batch if it exists
                if (!empty($params['body'])) {
                    $this->bulkIndex($params);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
