<?php

namespace App\Helpers;

class FormUnitUpdateData
{
    private $code;
    private $version;
    private $title;
    private $subtitle;
    private $description;
    private $status;
    private $updatedBy;

    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->version = $data['version'];
        $this->title = $data['title'];
        $this->subtitle = $data['subtitle'];
        $this->description = $data['description'];
        $this->status = $data['status'];
        $this->updatedBy = $data['updatedBy'];
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
