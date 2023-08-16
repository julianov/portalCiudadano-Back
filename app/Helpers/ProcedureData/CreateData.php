<?php

namespace App\Helpers\ProcedureData;

class CreateData
{

    public $user_id;
    public $actor_id;
    public $reason;
    public $forms;
    public $attachments;
    public $status;
    public $date_approv;

    public function __construct(array $data)
    {
        $this->user_id = $data['user_id'];
        $this->status = Status::CREATED;
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
            'status' => $this->status,
        ];
    }
}
