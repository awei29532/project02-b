<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class BadRequestException extends BaseException
{
    protected $code = 400;

    public function __construct($message, $logChannel, array $trace = [])
    {
        $this->logChannel = $logChannel;
        $this->message = $message;
        $this->trace = $trace;
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ], $this->getCode());
    }
}
