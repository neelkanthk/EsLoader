<?php

namespace Neelkanthk\EsLoader\Core\Elasticsearch;

use Exception;
use Elasticsearch;
use Neelkanthk\EsLoader\Core\Elasticsearch\Aws;
use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Neelkanthk\EsLoader\Core\Helper;

class Connector
{

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            $this->$property = $this->connection();
        }
        return $this->$property;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    public static function connection($config)
    {
        $connection = $config["connection"];
        switch ($connection) {
            case 'local':
                return self::local($config);
            case 'aws':
                return self::aws($config);
            default:
                return self::local($config);
        }
    }

    /**
     * Self Hosted ES
     * @return boolean
     */
    private static function local($config)
    {
        $config = $config['local'];
        $result = false;
        try {
            $client = Elasticsearch\ClientBuilder::create()// Instantiate a new ClientBuilder
                    ->setHosts($config)      // Set the hosts
                    ->setRetries(2)
                    ->build();
            $result = ($client instanceof Elasticsearch\Client) ? $client : false;
        } catch (Exception $ex) {
            $result = false;
        } finally {
            return $result;
        }
    }

    /**
     * AWS Hosted ES
     * @return boolean
     */
    private static function aws($config)
    {
        try {
//AWS
            $config = $config['aws'];

            $provider = CredentialProvider::fromCredentials(
                            new Credentials($config['access_key'], $config['secret_key'])
            );
// Create a handler (with the region of your Amazon Elasticsearch Service domain)
            $handler = new Aws($config['region'], $provider);
// Use this handler to create an Elasticsearch-PHP client
            $client = Elasticsearch\ClientBuilder::create()
                    ->setHandler($handler)
                    ->setHosts([$config['host']])
                    ->build();
            $result = ($client instanceof Elasticsearch\Client) ? $client : false;
        } catch (Exception $ex) {
            $result = false;
        } finally {
            return $result;
        }
    }

}
