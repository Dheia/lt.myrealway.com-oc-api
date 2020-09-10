<?php

class ApiResponse
{
    protected $data;

    /** @var ApiStorage */
    protected $storage;

    /**
     * ApiResponse constructor.
     * @param ApiStorage $storage
     */
    public function __construct(ApiStorage $storage)
    {
        $this->data = [];
        $this->storage = $storage;
    }

    public function addImage($size, $id)
    {
        return $this->addValue('img', $id, [
            'id'  => $id,
            $size => $this->storage->get("image/$size/$id") ?: null
        ]);
    }

    public function addObject($type, $key)
    {
        return $this->addValue($type, $key, $this->storage->getJson("$type/$key"));
    }

    public function addValue($type, $key, $value)
    {
        if (!isset($this->data[$type]))
        {
            $this->data[$type] = [];
        }

        $this->data[$type][$key] = $value;

        return $value;
    }

    public function set($data)
    {
        $this->data = $data;
    }

    public function json()
    {
//        return is_array($this->data) ? json_encode($this->data) : $this->data;
        return is_array($this->data) ? json_encode($this->data, JSON_PRETTY_PRINT) : $this->data;
    }

}
