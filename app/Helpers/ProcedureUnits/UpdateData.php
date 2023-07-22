<?php

namespace App\Helpers\ProcedureUnits;

class UpdateData
{
    private $id;
    private $title;
    private $state;
    private $description;
    private $forms;
    private $theme;
    private $updated_by;
    // attachments

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->state = $data['state'];
        $this->description = $data['description'];
        $this->forms = $data['forms'];
        $this->theme = $data['theme'];
        $this->updated_by = $data['updated_by'];
    }

    public function get(string $property)
    {
        return $this->$property || null;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'state' => $this->state,
            'description' => $this->description,
            'forms' => $this->forms,
            'theme' => $this->theme,
            'updated_by' => $this->updated_by,
        ];
    }
}
