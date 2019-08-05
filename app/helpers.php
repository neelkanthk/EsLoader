<?php

//Get Config
if (!function_exists('config')) {

    function config($key)
    {

        if (!empty($key)) {
            $config = include 'config.php';
            return $config[$key];
        }
    }

}

//Get file extension
if (!function_exists('get_file_extension')) {

    function get_file_extension($file)
    {
        $info = new SplFileInfo($file);
        return $info->getExtension();
    }

}

//Debugger
if (!function_exists('decho')) {

    function decho($expression)
    {
        var_dump($expression);
        die;
    }

}
if (!function_exists('print_mem')) {

    function print_mem()
    {
        /* Currently used memory */
        $mem_usage = memory_get_usage();

        /* Peak memory usage */
        $mem_peak = memory_get_peak_usage();

        echo 'The script is now using: ' . round($mem_usage / 1024) . 'KB of memory.' . PHP_EOL;
        echo 'Peak usage: ' . round($mem_peak / 1024) . 'KB of memory.' . PHP_EOL;
    }

}