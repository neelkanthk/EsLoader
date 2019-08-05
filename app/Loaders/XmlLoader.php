<?php

namespace Neelkanthk\EsLoader\Loaders;

use Neelkanthk\EsLoader\Core\AbstractLoader;
use Neelkanthk\EsLoader\Interfaces\LoaderInterface;

class XmlLoader extends AbstractLoader implements LoaderInterface
{

    protected $xml;

    public function __construct(string $filePath)
    {
        parent::__construct();
    }

    public function iterate(int $offset)
    {
        
    }

    public function index()
    {
        
    }

}
