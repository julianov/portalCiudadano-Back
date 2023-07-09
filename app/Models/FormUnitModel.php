<?php

namespace App\Models;

class FormUnitModel
{
    // Metadata
    private string $status;
    private string $createdAt;
    private string $updatedAt;
    private string $createdBy;
    private string $updatedBy;

    // Info
    private string $title;
    private string $subtitle;
    private string $description;

    // Inmutables
    private string $code;
    private int $version;
    private array $fields;

    private function __construct(array $data)
    {
        $this->status = $data['status'];
        $this->createdAt = $data['createdAt'];
        $this->updatedAt = $data['updatedAt'];
        $this->createdBy = $data['createdBy'];
        $this->updatedBy = $data['updatedBy'];

        $this->title = $data['title'];
        $this->subtitle = $data['subtitle'];
        $this->description = $data['description'];

        $this->code = $data['code'];
        $this->version = $data['version'];
        $this->fields = json_decode($data['forms'], true);
    }

    public function toArray(): array
    {
        return array_merge($this->getBasicInfo(), [
            'createdAt' =>  $this->createdAt,
            'updatedAt' =>  $this->updatedAt,
            'createdBy' =>  $this->createdBy,
            'updatedBy' =>  $this->updatedBy,
        ]);
    }

    public function get(string $key)
    {
        $properties = $this->toArray();

        return $properties[$key];
    }

    public function getBasicInfo(): array
    {
        return [
            'code' => $this->code,
            'version' => $this->version,
            'status' => $this->status,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'fields' => $this->fields,
        ];
    }
}
