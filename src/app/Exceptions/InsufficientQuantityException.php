<?php

namespace App\Exceptions;

use Exception;

class InsufficientQuantityException extends Exception
{
    protected $productId;
    protected $requestedQuantity;
    protected $availableQuantity;

    public function __construct($productId, $requestedQuantity, $availableQuantity)
    {
        $this->productId = $productId;
        $this->requestedQuantity = $requestedQuantity;
        $this->availableQuantity = $availableQuantity;

        $message = "Insufficient stock. Requested: {$requestedQuantity}, Available: {$availableQuantity}";
        parent::__construct($message);
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getRequestedQuantity()
    {
        return $this->requestedQuantity;
    }

    public function getAvailableQuantity()
    {
        return $this->availableQuantity;
    }

    public function context()
    {
        return [
            'product_id' => $this->productId,
            'requested' => $this->requestedQuantity,
            'available' => $this->availableQuantity,
        ];
    }
}
