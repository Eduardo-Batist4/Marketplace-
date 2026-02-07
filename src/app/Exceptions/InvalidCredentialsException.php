<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct(?string $message = null)
    {
        $message = $message ?? 'Invalid credentials provided. Please check your email and password';
        parent::__construct($message);
    }

    public function context(): array
    {
        return [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
    }
}
