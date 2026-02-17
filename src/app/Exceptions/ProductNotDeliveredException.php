<?php

namespace App\Exceptions;

use Exception;

class ProductNotDeliveredException extends Exception implements HttpExceptionInterface
{
    public function __construct()
    {
        parent::__construct("Your product has not yet been delivered. Feedback will only be possible once the item has been received!");
    }

    public function getStatusCode(): int { return 422; }
    public function getErrorType(): string { return 'Unprocessable Entity'; }
}
