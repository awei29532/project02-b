<?php

namespace App\Http\Controllers\CustomerService;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerService\SmsRequest;
use App\Models\Sms;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected $logChannel = 'smslog';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            if ($this->user->isAgent()) {
                throw new ForbiddenException();
            }
            return $next($request);
        });
    }

    /**
     * showdoc
     * @catalog 客服/簡訊相關API
     * @title 簡訊列表
     * @description 簡訊列表
     * @method get
     * @url /api/customer-service/sms
     * @return {"data":[{"id":"1","content":"asgasdf","send_at":"1010-10-10 10:10:10"}]}
     * @return_param id int ID
     * @return_param content string 內容
     * @return_param send_at string 傳送時間
     */
    public function index(Request $request)
    {
        $res = Sms::orderBy('status')->get();

        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 客服/簡訊相關API
     * @title 更新簡訊
     * @description 更新簡訊
     * @method patch
     * @url /api/customer-service/sms/{id}
     * @param id true int ID
     * @param content true string 內容
     * @param send_at true string 傳送時間
     */
    public function update(SmsRequest $request, $id)
    {
        $sms = Sms::findOrFail($id);
        $sms->content = $request->content;
        $sms->send_at = $request->send_at;
        $sms->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 客服/簡訊相關API
     * @title 刪除簡訊
     * @description 刪除簡訊
     * @method delete
     * @url /api/customer-service/sms/{id}
     * @param id true int ID
     */
    public function destroy($id)
    {
        Sms::destroy($id);
    }

    /**
     * showdoc
     * @catalog 客服/簡訊相關API
     * @title 發送簡訊
     * @description 發送簡訊
     * @method post
     * @url /api/customer-service/sms/send
     * @param id true int ID
     * @remark 發送功能未實作
     */
    public function send(Request $request)
    {
        // TODO:
        $sms = Sms::findOrFail($request->id);
        $sms->status = "1";
        $sms->saveOrFail();
    }
}
