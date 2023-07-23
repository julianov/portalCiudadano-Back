<?php

namespace App\Helpers\ProcedureUnits;

class CreateData
{
    private $title;
    private $state;
    private $description;
    private $forms;
    private $theme;
    private $attachments;
    private $created_by;
    // attachments

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->state = $data['state'];
        $this->description = $data['description'];
        $this->forms = trim(json_encode($data['forms']), '"');;
        $this->theme = $data['theme'];
        $this->attachments = trim(json_encode($data['attachments']), '"');;
        $this->created_by = $data['created_by'];
    }

    public function get(string $key)
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'state' => $this->state,
            'description' => $this->description,
            'forms' => $this->forms,
            'theme' => $this->theme,
            'attachments' => $this->attachments,
            'created_by' => $this->created_by,
        ];
    }
}
