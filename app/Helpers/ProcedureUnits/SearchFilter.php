<?php

namespace App\Helpers\ProcedureUnits;

class SearchFilter
{
    private $title = null;
    private $category = null;

    public function __construct(array $query)
    {
        // prioritize category over title
        if (isset($query['category'])) {
            $this->category = $query['category'];
            $this->title = null;
        } else if (isset($query['title'])) {
            $this->title = intval($query['title'], 10);
        }
    }

    public function getFilter()
    {
        return [
            'title' => $this->title,
            'category' => $this->category,
        ];
    }
}
