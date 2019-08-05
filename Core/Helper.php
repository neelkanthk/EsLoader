<?php

namespace Neelkanthk\EsLoader\Core;

use SplFileInfo;

class Helper
{

    public static function config($key)
    {
        if (!empty($key)) {
            $config = include 'config.php';
            return $config[$key];
        }
    }

    public static function getFileExtension($file)
    {
        $info = new SplFileInfo($file);
        return $info->getExtension();
    }

    public static function decho($expression)
    {
        var_dump($expression);
        die;
    }

    public static function memoryFootprint()
    {
        /* Currently used memory */
        $mem_usage = memory_get_usage();

        /* Peak memory usage */
        $mem_peak = memory_get_peak_usage();

        return [
            "currenty_used" => round($mem_usage / 1024) . 'KB',
            "peak_usage" => round($mem_peak / 1024) . 'KB'
        ];
    }

}
