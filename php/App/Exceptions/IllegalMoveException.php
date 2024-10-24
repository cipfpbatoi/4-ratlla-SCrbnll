<?php

namespace Joc4enRatlla\Exceptions;

class IllegalMoveException extends \Exception
{
    public function __construct($message = "Illegal move", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}