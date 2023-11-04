<?php

namespace App\Helpers\ProcedureData;

use App\Helpers\Pagination;

class GetListBySearchFilter
{
    private string $keyword = '';
    private int $start_position;
    private int $end_position;

    public function __construct(array $query)
    {

        $this->keyword = $query['keyword'];
        $this->start_position = $query['start_position'];
        $this->end_position = $query['end_position'];
    }

    public function toArray()
    {
        return [
            'keyword' => $this->keyword,
            'start_position' => $this->start_position,
            'end_position' => $this->end_position,
        ];

    }
}
