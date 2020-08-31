<?php

namespace App\Http\Controllers;

use App\CustomTrait\UserCheckMiddlewareTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, UserCheckMiddlewareTrait;

    protected $logChannel = 'daily';

    protected $slackLogChannel = 'slack';

    public function getLogChannel()
    {
        return $this->logChannel;
    }

    public function getSlackLogChannel()
    {
        return $this->slackLogChannel;
    }

    protected function returnData($res, $status = 200, array $headers = [])
    {
        $headers = array_merge([
            'content-type' => 'application/json'
        ], $headers);
        return response([
            'data' => $res,
        ], $status, $headers);
    }

    protected function returnPaginate($content, $res, $status = 200, array $headers = [])
    {
        $headers = array_merge([
            'content-type' => 'application/json'
        ], $headers);
        return response([
            'data' => [
                'content' => $content,
                'total' => $res->total(),
                'page' => $res->currentPage(),
                'per_page' => $res->perPage(),
                'last_page' => $res->lastPage(),
            ],
        ], $status, $headers);
    }
}
