<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception implements HttpExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Invalid credentials provided. Please check your email and password');
    }

    public function getStatusCode(): int { return 401; }
    public function getErrorType(): string { return 'Unauthorized'; }
}
