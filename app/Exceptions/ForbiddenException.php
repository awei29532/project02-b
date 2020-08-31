<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class ForbiddenException extends BaseException
{
    protected $code = 403;
    protected $needLog = false;

    public function __construct($message = '')
    {
        $this->message = $message ?: trans('common.forbidden');
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ], $this->getCode());
    }
}
