<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => "Permission denied!"
        ], 403);
    }
}
