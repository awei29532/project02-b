<?php

namespace App\Http\Controllers\CustomerService;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerService\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $logChannel = 'messagelog';

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
     * @catalog 客服/個人訊息相關API
     * @title 列表
     * @description 列表
     * @method get
     * @url /api/customer-service/message
     * @return {"data":[{"id":"1","content":"agrfh","status":"1","send_at":"1010-10-10 10:10:10"}]}
     */
    public function index(Request $request)
    {
        $res = Message::orderBy('status')->get();

        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 客服/個人訊息相關API
     * @title 更新訊息
     * @description 更新訊息
     * @method patch
     * @url /api/customer-service/message/{id}
     * @param id true int ID
     * @param content true string 內容
     * @param send_at true string 傳送時間
     */
    public function update(MessageRequest $request, $id)
    {
        $msg = Message::findOrFail($id);
        $msg->content = $request->content;
        $msg->send_at = $request->send_at;
        $msg->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 客服/個人訊息相關API
     * @title 刪除訊息
     * @description 刪除訊息
     * @method delete
     * @url /api/customer-service/message/{id}
     */
    public function destroy($id)
    {
        Message::destroy($id);
    }

    /**
     * showdoc
     * @catalog 客服/個人訊息相關API
     * @title 發送
     * @description 發送
     * @method post
     * @url /api/customer-service/message/send
     * @param id true int ID
     * @remark 發送功能未實作
     */
    public function send(Request $request)
    {
        $msg = Message::findOrFail($request->id);
        $msg->status = "1";
        $msg->saveOrFail();
    }
}
