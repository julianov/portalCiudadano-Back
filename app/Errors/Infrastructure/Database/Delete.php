<?php

namespace App\Errors\Infrastructure\Database;

class DatabaseDeleteError extends DatabaseError
{
    public function __construct()
    {
        $message = "Internal server problem, please try again later";

        parent::__construct($message, '004');
    }
}
