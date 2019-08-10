<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Prewk\XmlStringStreamer;
use Neelkanthk\EsLoader\Core\Helper;
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
            $iteration = 1;
            while ($xmlNode = $this->xml->getNode()) {
                $xmlNodeArray = (array) simplexml_load_string($xmlNode);
                if (!is_null($this->config["doc_id_key"])) {
                    $params['body'][] = [
                        'index' => [
                            '_index' => $this->config["index"],
                            '_type' => '_doc',
                            '_id' => $xmlNodeArray["id"]
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

                $params['body'][] = $xmlNodeArray;

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
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
