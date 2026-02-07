<?php

namespace App\Exceptions;

use Exception;

class MaxDiscountException extends Exception
{
    protected $requestedDiscount;
    protected $maxDiscount;

    public function __construct(?string $requestedDiscount, ?int $maxDiscount, ?int $currentDiscount)
    {
        $this->requestedDiscount = $requestedDiscount;
        $this->maxDiscount = $maxDiscount;

        $message = "Maximum discount exceeded. Requested: {$requestedDiscount}%, Current: {$currentDiscount}%, Maximum allowed: {$maxDiscount}%";
        parent::__construct($message);
    }

    public function getRequestedDiscount()
    {
        return $this->requestedDiscount;
    }

    public function getMaxDiscount()
    {
        return $this->maxDiscount;
    }

    public function context(): array
    {
        return [
            'requested_discount' => $this->requestedDiscount,
            'max_discount' => $this->maxDiscount,
        ];
    }
}
