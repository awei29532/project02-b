<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperationLogController extends Controller
{
    /**
     * showdoc
     * @catalog 帳號管理/操作紀錄相關API
     * @title 列表
     * @description 列表
     * @method post
     * @url /api/account/operation-log/list
     * @param started_at false string 起始時間
     * @param finished_at false string 結束時間
     * @param search_type true string 搜尋類型，0=全部，1=自己，2=客服主管，3=客服人員，4=大股東
     * @param username false string 帳號
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data": {"content":[{"id": "1", "opt_panel": "1", "operator": "admin", "object": "member01", "before": "", "after": "", "created_at": "1010-10-10 10:10:10"}], "page": "1", "per_page": "15", "last_page": "5", "total": "150"}}
     * @return_param id int ID
     * @return_param opt_panel int 前台/後台
     * @return_param operator string 操作者
     * @return_param object string 對象
     * @return_param before string 操作前
     * @return_param after string 操作後
     * @return_param created_at string 操作時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function list(Request $request)
    {
        // TODO:
    }
}
