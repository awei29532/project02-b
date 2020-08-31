<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        'authlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/auth/authlog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'gamesettinglog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/game/settinglog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'agentlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/agent/agentlog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'memberlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/member/memberlog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'customerservicelog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/agent/customerservicelog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'loginlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/loginlog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'subaccountlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/agent/subaccountlog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'archivelog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/archivelog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'callbacklog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/customer_service/callbacklog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'complaintletterlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/customer_service/complaintletterlog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'linesettinglog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/customer_service/linesettinglog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'smslog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/customer_service/smslog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'messagelog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/customer_service/messagelog.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'personallog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/personallog.log'),
            'level' => 'debug',
            'days' => 14,
        ]
    ],

];
