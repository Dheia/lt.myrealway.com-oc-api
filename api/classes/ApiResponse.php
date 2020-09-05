<?php

class ApiResponse
{
    protected $data;

    public function add($type, $key, $data)
    {
        if (!$this->data)
        {
            $this->data = [];
        }

        if (!isset($this->data[$type]))
        {
            $this->data[$type] = [];
        }

        $this->data[$type][$key] = $data;
    }

    public function set($data)
    {
        $this->data = $data;
    }

    public function json()
    {
        return is_array($this->data) ? json_encode($this->data, JSON_PRETTY_PRINT) : $this->data;
    }

}
