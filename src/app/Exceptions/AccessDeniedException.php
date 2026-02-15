<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception implements HttpExceptionInterface
{
    public function __construct()
    {
        parent::__construct('You do not have permission to perform this action');
    }

    public function getStatusCode(): int { return 403; }
    public function getErrorType(): string { return 'Forbidden'; }

}
