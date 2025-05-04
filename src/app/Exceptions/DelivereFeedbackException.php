<?php

namespace App\Exceptions;

use Exception;

class DelivereFeedbackException extends Exception
{
    public function render($request) 
    {
        return response()->json([
            'error' => "Your product has not yet been delivered. Feedback will only be possible once the item has been received!"
        ], 403);
    }
}
