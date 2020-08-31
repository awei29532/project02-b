<?php

namespace App\Http\Controllers\FrontSetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarqueeController extends Controller
{
    /**
     * showdoc
     * @catalog 前台設置/跑馬燈API
     * @title 跑馬燈列表
     * @description 跑馬燈列表
     * @method get
     * @url /api/front-setting/marquee
     * @param type false int 類型，
     * @param lang false string
     * @param status false int 帳號狀態，1=啟用，0=停用，all=全部
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":[{"id":"1","type":"1","lang":"zh-tw","status":"1","content":"asd321","updated_at":"2020-10-10 10:10:10","created_at":"2020-10-10 10:10:10"}],"page":"1","per_page":"15","last_page":"5","total":"150"}
     * @return_param id int ID
     * @return_param type int 1=重要公告，2=系統公告，3=活動公告
     * @return_param lang string 語系
     * @return_param status 啟停用，0=停用，1=啟用
     * @return_param content string 內容
     * @return_param updated_at string 更新時間
     * @return_param created_at string 創建時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function index(Request $request)
    {

    }

    /**
     * showdoc
     * @catalog 前台設置/跑馬燈API
     * @title 新增跑馬燈
     * @description 新增跑馬燈
     * @method put
     * @url /api/front-setting/marquee
     * @param lang true string 語系
     * @param type true int 類型，1=重要公告，2=系統公告，3=活動公告
     * @param status true int 狀態，0=停用，1=啟用
     * @param content true string 內容
     */
    public function stroe(Request $request)
    {

    }

    /**
     * showdoc
     * @catalog 前台設置/跑馬燈API
     * @title 跑馬燈細節
     * @description 跑馬燈細節
     * @method get
     * @url /api/front-setting/marquee
     * @param
     * @return
     */
    public function show(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }

    public function toggleEnabled(Request $request)
    {

    }
}
