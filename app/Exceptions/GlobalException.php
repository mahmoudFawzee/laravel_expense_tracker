<?php

namespace App\Exceptions;

use Exception;

class GlobalException extends Exception
{
    protected $message = 'error: Something Went Wrong';
}
