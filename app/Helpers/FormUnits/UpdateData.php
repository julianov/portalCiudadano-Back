<?php

namespace App\Helpers\FormUnits;

class UpdateData
{
    private $code;
    private $title;
    private $subtitle;
    private $description;
    private $elements;
    private $keywords;
    private $status;
    private $updated_by;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->subtitle = $data['subtitle'];
        $this->description = $data['description'];
        $this->elements = json_encode($data['elements']);
        $this->keywords = $data['keywords'];
        $this->status = $data['status'];
        $this->updated_by = $data['updated_by'];
    }

    public function get(string $key)
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'elements' => $this->elements,
            'keywords' => $this->keywords,
            'status' => $this->status,
            'updated_by' => $this->updated_by,
        ];
    }
}
