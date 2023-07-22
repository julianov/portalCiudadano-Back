<?php

namespace App\Helpers\ProcedureUnits;

class CreateData
{
    private $title;
    private $state;
    private $description;
    private $forms;
    private $theme;
    private $created_by;
    // attachments

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->state = $data['state'];
        $this->description = $data['description'];
        $this->forms = $data['forms'];
        $this->theme = $data['theme'];
        $this->created_by = $data['created_by'];
    }

    public function get(string $property)
    {
        return $this->$property || null;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'state' => $this->state,
            'description' => $this->description,
            'forms' => $this->forms,
            'theme' => $this->theme,
            'created_by' => $this->created_by,
        ];
    }
}
