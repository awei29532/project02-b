<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class ArchiveAccountController extends Controller
{
    protected $logChannel = 'archivelog';

    /**
     * showdoc
     * @catalog 帳號管理/封存帳號相關API
     * @title 列表
     * @description 列表
     * @method get
     * @url /api/account/archive-account
     * @param started_at false string 起始時間
     * @param finished_at false string 結束時間
     * @param search_type true string 搜尋類型，archive_time=封存時間，register_time=註冊時間，login_time=登入時間
     * @param username false string 帳號
     * @param nickname false string 暱稱
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data": {"content":[{"id": "1", "username": "zxc123", "nickname": "", "cell_phone": "0911111111", "register_ip": "168.95.1.1", "login_ip": "168.95.1.1", "archive_at": "1010-10-10 10:10:10", "register_at": "1010-10-10 10:10:10", "login_at": "1010-10-10 10:10:10", "remark": ""}], "page": "1", "per_page": "15", "last_page": "5", "total": "150"}}
     * @return_param id int ID
     * @return_param username string 帳號
     * @return_param nickname string 暱稱
     * @return_param cell_phone string 手機
     * @return_param register_ip string 註冊IP
     * @return_param register_at string 
     * @return_param login_ip string 登入時間
     * @return_param login_at string 登入時間
     * @return_param archive_at string 封存時間
     * @return_param remark string 備註
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $query = Member::onlyTrashed();

        $search_type = $request->search_type ?? 'archive_time';
        switch ($search_type) {
            default:
            case 'archive_time':
                $query->when($request->started_at, function ($q) use ($request) {
                    return $q->where('deleted_at', '>=', $request->started_at);
                })->when($request->finished_at, function ($q) use ($request) {
                    return $q->where('deleted_at', '<=', $request->finished_at);
                });
                break;
            case 'register_time':
                $query->when($request->started_at, function ($q) use ($request) {
                    return $q->where('created_at', '>=', $request->started_at);
                })->when($request->finished_at, function ($q) use ($request) {
                    return $q->where('created_at', '<=', $request->finished_at);
                });
                break;
            case 'login_time':
                $query->when($request->started_at, function ($q) use ($request) {
                    return $q->whereHas('latestLoginLog', function ($q) use ($request) {
                        $q->where('created_at', '>=', $request->started_at);
                    });
                })->when($request->finished_at, function ($q) use ($request) {
                    return $q->whereHas('latestLoginLog', function ($q) use ($request) {
                        $q->where('created_at', '<=', $request->finished_at);
                    });
                });
                break;
        }

        $username = $request->username ?? '';
        if ($username) {
            $query->where('username', 'like', "%$username%");
        }

        $nickname = $request->nickname ?? '';
        if ($nickname) {
            $query->where('nickname', 'like', "%$nickname%");
        }

        $per_page = intval($request->per_page ?? 15);
        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'username' => $row->username,
                    'nickname' => $row->nickname,
                    'cell_phone' => $row->cell_phone,
                    'register_ip' => $row->register_ip,
                    'register_at' => $row->created_at,
                    'login_ip' => $row->latestLoginLog->ip ?? '',
                    'login_at' => $row->latestLoginLog->created_at ?? '',
                    'archive_at' => $row->deleted_at,
                    'remark' => $row->remark,
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/封存帳號相關API
     * @title 移除手機
     * @description 移除手機
     * @method delete
     * @url /api/account/archive-account/{id}
     * @param id true int 帳號ID
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $member = Member::onlyTrashed()->findOrFail($id);
        $member->cell_phone = null;
        $member->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/封存帳號相關API
     * @title 修改備註
     * @description 修改備註
     * @method patch
     * @url /api/account/archive-account/{id}
     * @param id true int 會員ID
     * @param remark false string 備註
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $member = Member::onlyTrashed()->findOrFail($id);
        $member->remark = $request->remark;
        $member->saveOrFail();
    }
}
