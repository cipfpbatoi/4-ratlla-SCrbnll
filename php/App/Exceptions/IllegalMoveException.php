<?php

namespace Joc4enRatlla\Exceptions;

use Joc4enRatlla\Services\Logger;

class IllegalMoveException extends \Exception
{
    public function __construct($message = "Illegal move", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Logger::getInstance()->error($message);
    }
}