<?php

class ApiResponse
{
    protected $data = [];

    public function add($type, $key, $data)
    {
        if (!isset($this->data[$type]))
        {
            $this->data[$type] = [];
        }

        $this->data[$type][$key] = $data;
    }

    public function json()
    {
        return json_encode($this->data,JSON_PRETTY_PRINT);
    }

}
