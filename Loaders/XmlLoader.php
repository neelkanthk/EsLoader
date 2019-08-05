<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Interfaces\LoaderInterface;
use Prewk\XmlStringStreamer;
use Neelkanthk\EsLoader\Core\Helper;

class XmlLoader extends AbstractLoader
{

    protected $xml;

    public function __construct(string $filePath)
    {
        $this->xml = XmlStringStreamer::createStringWalkerParser($filePath);
        parent::__construct();
    }

    public function index()
    {
        $iteration = 1;
        while ($xmlNode = $this->xml->getNode()) {
            $xmlNodeArray = (array) simplexml_load_string($xmlNode);
            $params['body'][] = [
                'index' => [
                    '_index' => Helper::config("index"),
                    '_type' => 'doc',
                    '_id' => $xmlNodeArray["id"]
                ]
            ];
            $params['body'][] = $xmlNodeArray;

            // Every 100 elements stop and send the bulk request
            if ($iteration % 100 == 0) {
                $this->elasticsearch->bulk($params);
                $params = ['body' => []];
            }
            $iteration++;
        }
        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->elasticsearch->bulk($params);
        }
    }

}
