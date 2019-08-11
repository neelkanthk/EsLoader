<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use League\Csv\Reader;
use League\Csv\Statement;
use Exception;

class CsvLoader extends AbstractLoader
{

    protected $csv;

    public function __construct(string $filePath, array $config)
    {
        /**
         * If your CSV document was created or is read on a Macintosh computer,
         * add the following lines before using the library to help PHP detect line ending in Mac OS X
         */
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }

        $this->csv = Reader::createFromPath($filePath, 'r');
        $this->csv->setHeaderOffset(0);
        parent::__construct($config);
    }

    /**
     * Index data into Elasticsearch
     */
    public function index()
    {
        try {
            $batchSize = $this->config['batch_size'];
            $totalRecords = $this->csv->count();
            $offset = 0;
            while (true) {
                $chunk = (new Statement())->offset($offset)->limit($batchSize);
                $records = $chunk->process($this->csv);
                if ($records->count() > 0) {
                    $params = ['body' => []];
                    foreach ($records as $record) {
                        $offset++;
                        $this->prepareBulkIndexRequest($params, $record, $this->config);
                    }
                    $this->bulkIndex($params);
                }
                if ($offset >= $totalRecords) {
                    break;
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
