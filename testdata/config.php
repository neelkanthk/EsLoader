<?php

/**
 * NOTE: This package has been developed and tested on Elasticsearch 6.x
 * 
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
