<?php

namespace App\Exceptions;

use Exception;

class InsufficientQuantityException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Quantity exceeds available stock.'
        ], 422);
    }
}
