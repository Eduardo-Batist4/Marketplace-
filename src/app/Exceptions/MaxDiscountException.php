<?php

namespace App\Exceptions;

use Exception;

class MaxDiscountException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Discount cannot exceed 60%!'
        ], 422);
    }
}
