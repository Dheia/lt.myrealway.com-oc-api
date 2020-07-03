<?php namespace Qcsoft\Modeler\Classes;

class Util
{
    public static function safedir($path)
    {
        if (!is_dir($path))
        {
            mkdir($path);
        }

        return $path;
    }
}
