<?php

namespace App\Exceptions;

use Exception;

class NoItemsCartException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'No items in the cart!'
        ], 404);
    }
}
