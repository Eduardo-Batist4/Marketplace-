<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    public function render($request) 
    {
        return response()->json([
            'error' => "you don't have permission to access this!"
        ], 403);
    }
}
