<?php

namespace App\Exceptions;

use Exception;

class NoItemsInCartException extends Exception implements HttpExceptionInterface
{
    public function __construct(protected $message = null)
    {
        $message = $message ?? 'Your cart is empty. Please add items before proceeding to checkout';
        parent::__construct($message);
    }

    public function getStatusCode(): int { return 422; }
    public function getErrorType(): string { return 'Unprocessable Entity'; }
}
