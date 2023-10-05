<?php

namespace App\Helpers;

class Pagination {
    private int $start_position;
    private int $end_position;

    public function __construct(array $filter) {
        $this->start_position = $filter['start_position'];
        $this->end_position = $filter['end_position'];
    }

    public function toArray(): array {
        return [
            'start_position' => $this->start_position,
            'end_position' => $this->end_position,
        ];
    }

    public static function rules() {
        return [
            'start_position' => 'required|integer|min:0',
            'end_position' => 'required|integer|min:1|gte:start_position',
        ];
    }
}
