<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Interfaces\LoaderInterface;
use Neelkanthk\EsLoader\Core\JsonListener;
use JsonStreamingParser\Parser as JsonParser;

class JsonLoader extends AbstractLoader implements LoaderInterface
{

    protected $json;
    protected $listener;

    public function __construct(string $filePath)
    {
        parent::__construct();
        $this->json = fopen($filePath, 'rb');
        $this->listener = new JsonListener();
    }

    public function iterate(int $offset)
    {
        
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
                        '_index' => config("index"),
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
