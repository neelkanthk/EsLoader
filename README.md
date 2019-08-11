![](https://i.ibb.co/8bdmSg6/esloader-logo.png)

EsLoader is a lighweight PHP package for indexing data from multiple sources into Elasticsearch index.


**Table of Contents**

[TOCM]

[TOC]

### Features

- Supports CSV, XML and JSON files. 
- Support for MongoDb and MySQL coming soon in next release.
- Indexes data quickly by leveraging the bulk indexing feature of Elasticsearch.
- Supports integration of AWS Elasticsearch Service.
- Fully configurable - Define your own index name, custom mapping and settings and even set the size of each batch request.

**NOTE 1: This package is developed and tested on PHP 7.x and Elasticsearch 6.x.**

**NOTE 2: This package uses `_doc` for the  `_type` meta field of Elasticsearch document.  This setting is not configurable. To know more read : [Removal of mapping types.](https://www.elastic.co/guide/en/elasticsearch/reference/6.7/removal-of-types.html "Removal of mapping types.")**  

### Installation

`$ composer require neelkanthk/esloader`

### Usage

```php
require __DIR__ . '/vendor/autoload.php';

use Neelkanthk\EsLoader\Core\EsLoader;

//1. Specify the path of file to be indexed.
$filePath = __DIR__ . "/data.csv";
//2. Load array of configurations.
$config = [
    "index" => "esloader",
    "doc_id_key" => NULL,
    "connection" => "local",
    "local" => [
        'host' => "localhost",
        'port' => "9200"
    ],
    "aws" => [
        'host' => "",
        'region' => "",
        'access_key' => "",
        'secret_key' => ""
    ],
    "mappings" => [
        "_doc" => [
            '_source' => [
                'enabled' => true
            ],
            "properties" => [
                "id" => ['type' => 'keyword'],
                "first_name" => ['type' => 'text'],
                "last_name" => ['type' => 'text'],
                "email" => ['type' => 'keyword'],
                "gender" => ['type' => 'keyword'],
                "points" => ['type' => 'integer']
            ]
        ],
    ],
    "settings" => [
        'number_of_shards' => 2,
        'number_of_replicas' => 0
    ],
    "batch_size" => 100
];
//3. Pass the file and configuration to the `EsLoader::load` method.
EsLoader::load($filePath, $config);

```

### Configuration

```php
<?php

/**
 * index : Name of the Elasticsearch index
 * doc_id_key : The field in your dataset which you want to keep as Es document id. NULL assigns a Es auto generated id
 * connection : Set it `local` if you have a self managed Es cluster. For AWS hosted Es set it to `aws`
 * local : Es configuration for your self managed Es
 * aws : Es configuration for your AWS managed Es
 * mappings : Define Es mappings as per your dataset
 * settings : Define Es settings as per your requirements
 * batch_size : The number of documents to index in a single bulk index request
 * 
 */
return [
    "index" => "esloader",
    "doc_id_key" => NULL,
    "connection" => "local",
    "local" => [
        'host' => "localhost",
        'port' => "9200"
    ],
    "aws" => [
        'host' => "",
        'region' => "",
        'access_key' => "",
        'secret_key' => ""
    ],
    "mappings" => [
        "_doc" => [
            '_source' => [
                'enabled' => true
            ],
            "properties" => [
                "id" => ['type' => 'keyword'],
                "first_name" => ['type' => 'text'],
                "last_name" => ['type' => 'text'],
                "email" => ['type' => 'keyword'],
                "gender" => ['type' => 'keyword'],
                "points" => ['type' => 'integer']
            ]
        ],
    ],
    "settings" => [
        'number_of_shards' => 2,
        'number_of_replicas' => 0
    ],
    "batch_size" => 100
];

```

### Demo

The demo code is placed inside the **testdata** directory. The testdata contains sample csv, json and xml files having 1000 records each.

The configuration for the demo can be modified in **config.php** file.

To run the demo you need to have an elasticsearch cluster running. Once you have that you just need to run `php example.php` to run the demo.
