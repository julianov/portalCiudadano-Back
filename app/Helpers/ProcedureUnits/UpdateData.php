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
    private $attachments;
    private $updated_by;
    // attachments

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->state = $data['state'];
        $this->description = $data['description'];
        $this->forms = trim(json_encode($data['forms']), '"');
        $this->theme = $data['theme'];
        $this->attachments = trim(json_encode($data['attachments']), '"');
        $this->created_by = $data['updated_by'];
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
            'state' => $this->state,
            'description' => $this->description,
            'forms' => $this->forms,
            'theme' => $this->theme,
            'attachments' => $this->attachments,
            'updated_by' => $this->updated_by,
        ];
    }
}
