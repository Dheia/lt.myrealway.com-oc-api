<?php

class ApiStorage
{
    protected $basedir;

    public function __construct($basedir)
    {
        $this->basedir = $basedir;
    }

    public function get($filename)
    {
        $filepath = "{$this->basedir}/$filename";

        if (!file_exists($filepath))
        {
            return false;
        }

        $result = file_get_contents($filepath);

        return $result;
    }

    public function getJson($filename)
    {
        $result = $this->get("$filename.json");

        return $result ? json_decode($result) : false;
    }
}
