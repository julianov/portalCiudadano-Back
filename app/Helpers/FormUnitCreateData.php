<?php

namespace App\Helpers;

class FormUnitCreateData
{
    private $code;
    private $title;
    private $subtitle;
    private $description;
    private $elements;
    private $keywords;
    private $status;
    private $createdBy;

    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->title = $data['title'];
        $this->subtitle = $data['subtitle'];
        $this->description = $data['description'];
        $this->elements = $data['elements'];
        $this->keywords = $data['keywords'];
        $this->status = $data['status'];
        $this->createdBy = $data['createdBy'];
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
            'createdBy' => $this->createdBy,
        ];
    }
}
