<?php

namespace App\Repositories\Utitilies;

class Result
{
    public $status;
    public $data;

    public function __construct(array $queryResult)
    {
        // the result seems to be
        // {
        //     "headers": {},
        //     "original": {
        //         "result": array || null
        //     },
        //     "exception": null
        // }
        // but only the admissible data is in queryResult[0]->result

        $this->status = true;
        $this->data = $queryResult[0]->result;
    }
}
