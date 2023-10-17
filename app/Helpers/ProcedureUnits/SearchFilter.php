<?php

namespace App\Helpers\ProcedureUnits;

use App\Helpers\Pagination;

class SearchFilter extends Pagination
{
    private string $keyword = '';

    public function __construct(array $query)
    {
        parent::__construct($query);

        $this->keyword = $query['keyword'];
    }

    public function toArray()
    {
        $pagination_filter = parent::toArray();

        return array_merge($pagination_filter, [
            'keyword' => $this->keyword,
        ]);
    }
}
