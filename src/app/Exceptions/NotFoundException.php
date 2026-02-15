<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception implements HttpExceptionInterface
{
    public function __construct(private string $resource = 'Resource')
    {
        parent::__construct("{$resource} not found");
    }

    public function getStatusCode(): int { return 404; }
    public function getErrorType(): string { return 'Not Found'; }
}
