<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordException extends Exception
{
    protected $message = 'The provided password is invalid.';
}
