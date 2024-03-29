<?php

namespace App\Helpers\FormData;

class UpdateData
{
  
    public $form_unit_code;
    public $procedure_data_id;
    public $user_id;
    public $elements;

    public function __construct(array $data)
    {
        $this->form_unit_code = $data['form_unit_code'];
        $this->procedure_data_id = intval($data['procedure_data_id']);
        $this->user_id = $data['user_id'];
        $this->elements = trim(json_encode($data['form_data']),'"');
    }

    public function get(string $key)
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function set(string $key, $value)
    {
        $this->$key = $value;
    }

    public function toArray()
    {
        return [
            'form_unit_code' => $this->form_unit_code,
            'procedure_data_id' => $this->procedure_data_id,
            'user_id' => $this->user_id,
            'elements' => $this->elements,
        ];
    }
}