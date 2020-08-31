<?php

namespace App\Exceptions;

use App\CustomTraits\LogTrait;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use LogTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Create a new exception handler instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->dontReports();
    }

    /**
     * do not log to laravel error log
     */
    protected function dontReports()
    {
        $exceptionPath = scandir(app_path() . '/Exceptions');

        collect($exceptionPath)->map(function ($item) {
            if (preg_match('/Exception/i', $item)) {
                array_push($this->dontReport, 'App\\Exceptions\\' . substr($item, 0, -4));
            }
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // return parent::render($request, $exception);
        $res = $this->laravelExceptions($exception);

        if ($res instanceof Response || $res instanceof ResponseFactory)
            return $res;

        if (env('APP_DEBUG'))
            return parent::render($request, $exception);

        $res = $this->logging($request, $exception);
        if ($res instanceof Response || $res instanceof ResponseFactory)
            return $res;

        if (method_exists($exception, 'render'))
            return $exception->render();

        return response('', 500);
    }

    /**
     * logging
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     */
    public function logging($request, $exception)
    {
        try {
            ['logChannel' => $logChannel, 'slackLogChannel' => $slackLogChannel] = $this->logChannel($request, $exception);
        } catch (Exception $e) {
            // 預期外的錯誤
            // $this->doLog('daily', $e);
            return response('', 500);
        } catch (Throwable $e) {
            // 預期外的錯誤
            // $this->doLog('daily', $e);
            return response('', 500);
        }

        if ($this->needLogStatus($exception)) {
            if ($this->needSlackLogStatus($exception))
                $this->doLog($slackLogChannel, $exception);

            $this->doLog($logChannel, $exception);
        }

        return $this;
    }

    /**
     * handle laravel exceptions
     *
     * @param \Throwable $exception
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|App\Exceptions\handler
     */
    public function laravelExceptions($exception)
    {
        switch (true) {
            case $exception instanceof NotFoundHttpException:
                return response('', 404);
                break;
            case $exception instanceof MethodNotAllowedHttpException:
                return response('', 405, $exception->getHeaders());
                break;
            case $exception instanceof ValidationException:
                return response(['error' => $exception->validator->errors()], 422);
                break;
            case $exception instanceof ModelNotFoundException:
                return response('', 422);
                break;
        }

        return $this;
    }

    /**
     * need log status
     *
     * @param \Throwable $exception
     *
     * @return boolean
     */
    protected function needLogStatus($exception)
    {
        return !method_exists($exception, 'getNeedLogStatus') || (method_exists($exception, 'getNeedLogStatus') && $exception->getNeedLogStatus());
    }

    /**
     * need slack log
     *
     * @param \Throwable $exception
     *
     * @return boolean
     */
    protected function needSlackLogStatus($exception)
    {
        return method_exists($exception, 'getNeedSlackLogStatus') && $exception->getNeedSlackLogStatus();
    }

    /**
     * get log channel & slack log channel
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     *
     * @return array
     */
    protected function logChannel($request, $exception)
    {
        $logChannel = $this->getLogChannel($request, $exception, 'getLogChannel', 'daily');

        $slackLogChannel = $this->getLogChannel($request, $exception, 'getSlackLogChannel', 'slack');

        return compact('logChannel', 'slackLogChannel');
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @param string $getChannelMethod
     * @param string $defaultChannel
     *
     * @return string
     */
    protected function getLogChannel($request, $exception, string $getChannelMethod = 'getLogChannel', string $defaultChannel = 'daily')
    {
        $logChannel = method_exists($exception, $getChannelMethod)
            ? call_user_func([$exception, $getChannelMethod])
            : $this->checkRouteController($request, $getChannelMethod, $defaultChannel);

        return $this->checkChannelExists($logChannel);
    }

    /**
     * check route controller method
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $getChannelMethod
     * @param string $defaultChannel
     *
     * @return string
     */
    protected function checkRouteController($request, string $getChannelMethod = 'getLogChannel', string $defaultChannel = 'daily')
    {
        $route = $request->route();

        return method_exists($route, 'getController') ? call_user_func([$route->getController(), $getChannelMethod]) : $defaultChannel;
    }

    /**
     * check log channel
     *
     * @param string $logChannel
     *
     * @return string
     */
    protected function checkChannelExists(string $logChannel)
    {
        if ($logChannel && !array_key_exists($logChannel, config('logging.channels')))
            return response("unknow log channel {$logChannel}", 500);

        return $logChannel;
    }

    /**
     * do log
     *
     * @param string $logChannel
     * @param \Throwable  $exception
     */
    protected function doLog(string $logChannel = 'daily', $exception)
    {
        $logData = [
            'message' => $exception->getMessage(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $this->handleTrace($exception)
        ];

        if (method_exists($exception, 'getData')) {
            $logData['data'] = $exception->getData() ?? '';
        }

        return $this->writeToLog($logChannel, $logData);
    }

    /**
     * handle exception
     *
     * @param \Throwable  $exception
     *
     * @return \Illuminate\Support\Collection
     */
    protected function handleTrace($exception)
    {
        return collect($exception->getTrace())->filter(function ($trace, $index) {
            if ($index < 10)
                return $trace;
        })->map(function ($trace) {
            return Arr::except($trace, ['args']);
        });
    }

    /**
     * write log
     *
     * @param string $channel
     * @param array $logData
     */
    private function writeToLog(string $channel = 'daily', array $logData = [])
    {
        if (!env('APP_DEBUG')) {
            if ($channel == 'slack')
                $this->logToSlack($channel, print_r($logData, true), $logData, 'error');
            else
                $this->logToFile($channel, 'exceptions', $logData, 'error');
        }

        return $this;
    }
}
