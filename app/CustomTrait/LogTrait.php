<?php

namespace App\CustomTraits;

use Illuminate\Support\Facades\Log;

trait LogTrait
{
    /**
     * Do log to file
     *
     * @param string $channel
     * @param string $message
     * @param array|string $logData
     * @param string $level
     */
    public function logToFile(string $channel = 'daily', string $message = '', $logData = [], string $level = 'info')
    {
        Log::channel($channel)->$level($message, $logData);

        return $this;
    }

    /**
     * Do log to slack
     *
     * @param string $channel
     * @param string $message
     * @param array|string $logData
     * @param string $level
     */
    public function logToSlack(string $channel = 'daily', string $message = '', $logData = [], string $level = 'info')
    {
        return $this->logToFile($channel, print_r($message, true), $logData, $level);
    }
}
