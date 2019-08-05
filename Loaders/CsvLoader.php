<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Interfaces\LoaderInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Neelkanthk\EsLoader\Core\Helper;

class CsvLoader extends AbstractLoader
{

    protected $csv;

    public function __construct(string $filePath)
    {
        $this->csv = Reader::createFromPath($filePath, 'r');
        $this->csv->setHeaderOffset(0);
        parent::__construct();
    }

    public function index()
    {
        $totalBatch = (count($this->csv) / 100) + 1;
        $currentBatch = 1;
        $offset = 0;
        while ($currentBatch <= $totalBatch) {
            $chunk = (new Statement())->offset($offset)->limit(100);
            $records = $chunk->process($this->csv);
            if ($records->count() > 0) {
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
                if (!empty($params)) {
                    $this->elasticsearch->bulk($params);
                }
            }
            $offset = ($currentBatch - 1) * 100;
            $currentBatch++;
        }
    }

}
