<?php

namespace App\Helpers\ProcedureUnits;

class UpdateData
{
    private $id;
    private $title;
    private $state;
    private $description;
    //private $secretary;
    private $forms;
    private $theme;
    private $attachments;
    private $citizen_level;
    private $updated_by;
    // attachments

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->forms = trim(json_encode($data['forms']), '"');
        $this->description = $data['description'];

        $this->state = $data['state'];
        //$this->secretary = $data['secretary'];
        $this->attachments = trim(json_encode($data['attachments']), '"');
        $this->citizen_level = $data['citizen_level'];
        $this->updated_by = $data['updated_by'];

        if (isset($data['theme'])) {
            $this->theme = $data['theme'];
        }else{
            $this->theme = "";
        }
    }

    public function get(string $key)
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'theme' => $this->theme,
            'forms' => $this->forms,
            'description' => $this->description,
            'state' => $this->state,
            //'secretary' => $this->secretary,
            'attachments' => $this->attachments,
            'citizen_level' => $this->citizen_level,
            'updated_by' => $this->updated_by,
        ];
    }
}
