<?php

namespace App\Helpers\ProcedureData;

class UpdateData
{
    public $id;
    public $user_id;
    public $status;
    public $updated_by;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->user_id = $data['user_id'];
        $this->status = $data['status'];
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
            'status' => $this->status,
        ];
    }
}
