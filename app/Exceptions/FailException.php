<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class FailException extends BaseException
{
    protected $code = 500;

    public function __construct($message = '', $logChannel, array $trace = [])
    {
        $this->logChannel = $logChannel;
        $this->message = $message;
        $this->trace = $trace;
    }
}
