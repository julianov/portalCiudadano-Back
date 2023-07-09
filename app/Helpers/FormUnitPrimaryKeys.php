<?php

namespace App\Helpers;

class FormUnitPrimaryKeys
{
    private $primaryKeys;

    public function __construct(array $primaryKeys)
    {
        $this->primaryKeys = $primaryKeys;
    }

    public function toArray(): array
    {
        return [
            'primaryKeys' => array_map(function ($element) {
                return $element->toArray();
            }, $this->primaryKeys)
        ];
    }
}

class FormUnitPrimaryKey
{
    private $code;
    private $version;

    public function __construct(array $primaryKeys)
    {
        $this->code = $primaryKeys['code'];
        $this->version = $primaryKeys['version'];
    }

    public function get(string $key): mixed
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'version' => $this->version
        ];
    }
}
