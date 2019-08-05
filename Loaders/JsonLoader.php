<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Interfaces\LoaderInterface;
use Neelkanthk\EsLoader\Loaders\JsonListener;
use JsonStreamingParser\Parser as JsonParser;
use Neelkanthk\EsLoader\Core\Helper;

class JsonLoader extends AbstractLoader
{

    protected $json;
    protected $listener;

    public function __construct(string $filePath)
    {
        $this->json = fopen($filePath, 'rb');
        $this->listener = new JsonListener();
        parent::__construct();
    }

    public function index()
    {
        $parser = new JsonParser($this->json, $this->listener);
        $parser->parse();
        $records = $this->listener->getJson();
        if (count($records) > 0) {
            foreach ($records as $record) {
                $params['body'][] = [
                    'index' => [
                        '_index' => Helper::config("index"),
                        '_type' => 'doc',
                        '_id' => $record["id"]
                    ]
                ];
                $params['body'][] = $record;
            }
            $this->elasticsearch->bulk($params);
        }
    }

}
