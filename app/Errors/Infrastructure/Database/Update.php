<?php

namespace App\Errors\Infrastructure\Database;

class DatabaseUpdateError extends DatabaseError
{
    public function __construct()
    {
        $message = "Internal server problem, please try again later";

        parent::__construct($message, '003');
    }
}
