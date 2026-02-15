<?php

namespace App\Exceptions;

interface HttpExceptionInterface
{
    public function getMessage(): string;
    public function getErrorType(): string;
    public function getStatusCode(): int;
}
