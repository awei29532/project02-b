<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class ValidateException extends BaseException
{
    protected $code = 422;
    protected $needLog = false;

    public function __construct($message = '')
    {
        $this->message = $message;
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ], $this->getCode());
    }
}
