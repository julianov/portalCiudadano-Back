<?php

namespace App\Helpers\FormData;

class UpdateData
{
    public int $id;
    public string $elements;
    public string $attachment_names;


    public function __construct(array $data)
    {
        $this->id = $data['form_data_id'];
        $this->elements = trim(json_encode($data['elements']),'"');
        $this->attachment_names = json_encode($data['attachment_names']);
    }

    public function get(string $key)
    {
        return $this->$key;
    }

    public function set(string $key, $value)
    {
        $this->$key = $value;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'elements' => $this->elements,
            'attachment_names' => $this->attachment_names,
        ];
    }
}
