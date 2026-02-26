<?php

namespace App\Exceptions;

use Exception;

class TokenException extends Exception implements HttpExceptionInterface
{

    public function __construct(protected string $resource)
    {
        $message = $resource ? "Invalid or Expired $resource" : "Invalid or Expired Token";
        parent::__construct($message);
    }

    public function getStatusCode(): int { return 401; }
    public function getErrorType(): string { return 'Unauthorized'; }
}
