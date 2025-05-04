<?php

namespace App\Exceptions;

use Exception;

class AlreadyGivenFeedbackException extends Exception
{
    public function render($request) 
    {
        return response()->json([
            'error' => "You have already given feedback!"
        ], 403);
    }
}
