<?php

namespace App\Exceptions;

use Exception;

class InsufficientQuantityException extends Exception implements HttpExceptionInterface
{
    public function __construct(int $productId, int $requestedQuantity, int $availableQuantity)
    {
        parent::__construct("Insufficient stock. Requested: {$requestedQuantity}, Available: {$availableQuantity}");
    }

    public function getStatusCode(): int { return 400; }
    public function getErrorType(): string { return 'Bad Request'; }
}
