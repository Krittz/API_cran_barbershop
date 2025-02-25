<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordException extends Exception
{
    public function __construct($message = "A senha está incorreta.", $code = 422)
    {
        parent::__construct($message, $code);
    }
}
