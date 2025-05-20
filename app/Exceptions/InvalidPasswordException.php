<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordException extends GlobalException
{
    public function __construct(string $field = 'password', string $message = 'The provided password is invalid.')
    {
        $this->message = $message;
        $this->field = $field;
    }
}
