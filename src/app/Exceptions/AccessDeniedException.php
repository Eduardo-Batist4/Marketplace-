<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    protected $action;

    public function __construct($message = null, $action = null)
    {
        $this->action = $action;
        $message = $message ?? 'You do not have permission to perform this action';
        parent::__construct($message);
    }

    public function getAction()
    {
        return $this->action;
    }

    public function context()
    {
        return [
            'action' => $this->action,
            'user_id' => auth()->id(),
        ];
    }
}
