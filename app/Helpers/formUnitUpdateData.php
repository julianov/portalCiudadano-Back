<?php

namespace App\Helpers;

class FormUnitUpdateData
{
    private $code;
    private $title;
    private $subtitle;
    private $description;
    private $elements;
    private $keywords;
    private $status;
    private $created_by;

    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->title = $data['title'];
        $this->subtitle = $data['subtitle'];
        $this->description = $data['description'];
        $this->elements = trim(json_encode($data['elements']), '"');
        $this->keywords = $data['keywords'];
        $this->status = $data['status'];
        $this->created_by = $data['created_by'];
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'version' => $this->version,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'status' => $this->status,
            'updatedBy' => $this->updatedBy,
        ];
    }
}
