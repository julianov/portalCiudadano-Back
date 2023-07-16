<?php

namespace App\Helpers\FormUnits;

class CreateData
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
            'created_by' => $this->created_by,
        ];
    }
}
