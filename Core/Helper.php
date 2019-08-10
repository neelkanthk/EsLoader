<?php

namespace Neelkanthk\EsLoader\Core;

use SplFileInfo;

class Helper
{

    /**
     * Get extension of file
     * 
     * @param type $file
     * @return type
     */
    public static function getFileExtension($file)
    {
        $info = new SplFileInfo($file);
        return $info->getExtension();
    }

    /**
     * Debugging method
     * 
     * @param type $expression
     */
    public static function decho($expression)
    {
        var_dump($expression);
        die;
    }

}
