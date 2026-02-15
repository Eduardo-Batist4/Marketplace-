<?php

namespace App\Exceptions;

use Exception;

class AlreadyGivenFeedbackException extends Exception implements HttpExceptionInterface
{
    public function __construct()
    {
        parent::__construct("You have already given feedback for this order");
    }

    public function getStatusCode(): int { return 400; }
    public function getErrorType(): string { return 'Bad Request'; }
}
