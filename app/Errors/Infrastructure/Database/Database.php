<?php

namespace App\Errors\Infrastructure\Database;

use Exception;

class DatabaseError extends Exception
{
    private string $internalCode;

    public function __construct(string $message, string $internalCode)
    {
        parent::__construct($message);
        $this->internalCode = $internalCode;
    }

    public function getInternalCode(): string
    {
        return $this->internalCode;
    }
}
