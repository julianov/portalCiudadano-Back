<?php

namespace App\Helpers\ProcedureData;

class UpdateData
{

    public $id;
    public $state;

    public function __construct(array $data)
    {
        $this->id = $data['procedure_data_id'];
        $this->state = $data['state'];
    }

    public function get(string $key)
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function set(string $key, $value)
    {
        $this->{$key} = $value;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
        ];
    }
}
