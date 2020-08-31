<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class UnauthorizedException extends BaseException
{
    protected $code = 401;
    protected $logChannel = 'authlog';
    protected $needLog = false;
    protected $resData = [];

    public function __construct($message = '', $resData = [], $logChannel = 'authlog')
    {
        $this->message = $message;
        $this->logChannel = $logChannel;
        $this->resData = $resData;
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ] + $this->resData, $this->getCode());
    }
}
