<?php

namespace App\Helpers\FormUnits;

use App\Helpers\Pagination;

class GetListBySearchFilter extends Pagination
{
    private string $keyword = '';

    public function __construct(array $filter)
    {
        parent::__construct($filter);

        $this->keyword = $filter['keyword'];
    }

    public function toArray() {
        return array_merge(
            parent::toArray(),
            [
                'search' => $this->keyword,
            ]
        );
    }
}
