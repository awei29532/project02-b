<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    /** @var string log channel */
    protected $logChannel = 'daily';
    /** @var integer|string http status code */
    protected $code = 400;
    /** @var string exception message */
    protected $message = '';
    /** @var array exception trace */
    protected $trace = [];
    /** @var string slack log channel */
    protected $slackLogChannel = 'slack';
    /** @var mixed */
    protected $data = null;

    /** @var boolean 是否要記錄 Log */
    protected $needLog = true;
    /** @var boolean 是否要記錄 slack Log */
    protected $needSlackLog = false;

    /**
     * get need log status
     *
     * @return boolean
     */
    public function getNeedLogStatus()
    {
        return $this->needLog;
    }

    /**
     * get need slack log status
     *
     * @return boolean
     */
    public function getNeedSlackLogStatus()
    {
        return $this->needSlackLog;
    }

    /**
     * get log channel
     *
     * @return string
     */
    public function getLogChannel()
    {
        return $this->logChannel;
    }

    /**
     * get slack log channel
     *
     * @return string
     */
    public function getSlackLogChannel()
    {
        return $this->slackLogChannel;
    }

    /**
     * get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * render
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function render()
    {
        return response('', $this->getCode());
    }
}
