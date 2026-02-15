<?php

namespace App\Exceptions;

use Exception;

class MaxDiscountException extends Exception implements HttpExceptionInterface
{
    public function __construct(string|null $requestedDiscount, int|null $maxDiscount, int|null $currentDiscount)
    {
        $message = "Maximum discount exceeded. Requested: {$requestedDiscount}%, Current: {$currentDiscount}%, Maximum allowed: {$maxDiscount}%";
        parent::__construct($message);
    }

    public function getStatusCode(): int { return 400; }
    public function getErrorType(): string { return 'Bad Request'; }
}
