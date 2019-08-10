<?php

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
    "debugging" => true
];
