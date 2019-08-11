<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Prewk\XmlStringStreamer;
use Exception;

class XmlLoader extends AbstractLoader
{

    protected $xml;

    public function __construct(string $filePath, array $config)
    {
        $this->xml = XmlStringStreamer::createStringWalkerParser($filePath);
        parent::__construct($config);
    }

    /**
     * Index data into Elasticsearch
     */
    public function index()
    {
        try {
            $batchSize = $this->config['batch_size'];
            $iteration = 1;
            $params = ['body' => []];
            while ($xmlNode = $this->xml->getNode()) {
                $record = (array) simplexml_load_string($xmlNode);
                $this->prepareBulkIndexRequest($params, $record, $this->config);
                // Every 100 elements stop and send the bulk request
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
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
