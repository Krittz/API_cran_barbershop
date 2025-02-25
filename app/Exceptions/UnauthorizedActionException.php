<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedActionException extends Exception
{
    public function __construct($message = "Você não tem permissão para realizar esta ação.", $code = 403)
    {
        parent::__construct($message, $code);
    }
}
