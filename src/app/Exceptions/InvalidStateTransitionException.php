<?php

namespace App\Exceptions;

use Exception;

class InvalidStateTransitionException extends Exception implements HttpExceptionInterface
{
    public function __construct($status, $newStatus)
    {
        parent::__construct("Invalid transition: from {$status} to {$newStatus}.");
    }

    public function getStatusCode(): int { return 422; }
    public function getErrorType(): string { return 'Unprocessable'; }
}
