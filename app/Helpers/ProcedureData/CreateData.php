<?php

namespace App\Helpers\ProcedureData;

class CreateData
{
    public $user_id;
    public $procedure_unit_id;

    public function __construct(array $data)
    {
        $this->user_id = $data['user_id'];
        $this->procedure_unit_id = $data['procedure_unit_id'];
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
            'user_id' => $this->user_id,
            'procedure_unit_id' => $this->procedure_unit_id,
        ];
    }
}
