<?php

namespace Neelkanthk\EsLoader\Interfaces;

interface LoaderInterface
{

    public function iterate(int $offset);

    public function index();
}
