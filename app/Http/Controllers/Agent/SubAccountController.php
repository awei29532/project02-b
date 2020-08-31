<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Sub\SubAddRequest;
use App\Http\Requests\Account\Sub\SubChangePasswordRequest;
use App\Http\Requests\Account\Sub\SubEditRequest;
use App\Http\Requests\Account\Sub\SubToggleEnabledRequest;
use App\Models\SubAccount;
use App\Models\User;
use App\Service\LoginLogService;
use App\Service\SubAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class SubAccountController extends Controller
{
    protected $logChannel = 'subaccountlog';

    protected $subAccountService;

    public function __construct(SubAccountService $subAccountService)
    {
        $this->subAccountService = $subAccountService;
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 子帳號列表
     * @description 子帳號列表
     * @method get
     * @url /api/account/sub-account
     * @param username false string 帳號
     * @param nickname false string 暱稱
     * @param status false string 會員狀態，0=停用，1=啟用，all=全部
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data": {"content":[{"id": "1", "username": "S01_Admin", "nickname": "", "status": "1"}], "page": "1", "per_page": "15", "last_page": "5", "total": "150"}}
     * @return_param id int ID
     * @return_param username string 帳號
     * @return_param status int 狀態
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     * @remark username、nickname => 模糊搜尋
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = SubAccount::where('extend_id', $user->id);

        $username = $request->username ?? '';
        if ($username) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->where('username', 'like', "%$username%");
            });
        }

        $nickname = $request->nickname ?? '';
        if ($nickname) {
            $query->whereHas('user', function ($q) use ($nickname) {
                $q->where('nickname', 'like', "%$nickname%");
            });
        }

        $status = $request->status ?? 'all';
        if ($status != 'all') {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $per_page = intval($request->per_page ?? 15);
        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'username' => $row->user->username,
                    'nickname' => $row->user->nickname,
                    'status' => $row->user->status,
                    'updated_at' => $row->udpated_at,
                    'created_at' => $row->created_at,
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 新增子帳號
     * @description 新增子帳號
     * @method post
     * @url /api/account/sub-account
     * @json_param {"username": "abc", "password":"123456", "nickname": "nick", "view_member_type": "1", "permissionids": "1,2,3"}
     * @param username true string 帳號
     * @param password true string 密碼，最少六碼，最大10碼
     * @param nickname false string 暱稱
     * @param view_member_type true int 檢視會員類型，0=全部，1=直客，2=代理會員
     * @param permissionids false string 1,2,3
     */
    public function store(SubAddRequest $request)
    {
        $user = auth()->user();

        DB::transaction(function () use ($request, $user) {
            $newUser = new User();
            $newUser->identity = $user->identity;
            $newUser->username = $request->username;
            $newUser->password = Hash::make($request->password);
            $newUser->nickname = $request->nickname ?? '';
            $newUser->saveOrFail();

            $permissionIds = $request->input('permissionids');

            if ($permissionIds) {
                if (preg_match('/,/i', $permissionIds))
                    $permissionIds = array_map('intval', explode(',', $permissionIds));
                else
                    $permissionIds = (int) $permissionIds;

                $ids = $this->subAccountService->takeOffWriteWithGiveIds($permissionIds);

                // 權限為空
                if ($ids->isEmpty())
                    throw new ForbiddenException();

                $roleName = $user->roles->pluck('name')->pop();

                User::find($newUser->id)->assignRole("{$roleName}_sub");
                User::find($newUser->id)->givePermissionTo($ids);
            }

            $sub = new SubAccount();
            $sub->user_id = $newUser->id;
            $sub->extend_id = $user->id;
            $sub->saveOrFail();
        });
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 子帳號資料
     * @description 子帳號資料
     * @method get
     * @url /api/account/sub-account/{id}
     * @return {"data": {"id":"1","username": "A01_Admin", "nickname": "", "created_at": "1010-10-10 10:10:10", "login_ip": "168.95.1.1", "login_at": "1010-10-10 10:10:10", "same_login_ip": "1", "view_member_type": "1", "permissions": ["1", "2", "5"]}}
     * @return_param id int 子帳號ID
     * @return_param username string 帳號
     * @return_param nickname: string 暱稱
     * @return_param created_at string 創建時間
     * @return_param login_at string 最後登入時間
     * @return_param login_ip string 最後登入IP
     * @return_param same_login_ip int 登入IP是否重複，1=有，0=無
     * @return_param view_member_type int 0=全部會員，1=直客，2=代理會員
     * @return_param permissions array 帳號功能
     */
    public function show($id)
    {
        $sub = SubAccount::findOrFail($id);

        return $this->returnData(
            [
                'id' => $sub->user->id,
                'username' => $sub->user->username,
                'nickname' => $sub->user->nickname ?? '',
                'updated_at' => $sub->updated_at,
                'created_at' => $sub->created_at,
                'login_at' => $sub->user->latestLoginLog->created_at ?? '',
                'login_ip' => $sub->user->latestLoginLog->ip ?? '',
                'same_login_ip' => $sub->user->ipExists(),
                'view_member_type' => '',
                'permission' => $this->subAccountService->getUpdatePermissionTree($id),
            ]
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 編輯子帳號
     * @description 編輯子帳號
     * @method patch
     * @url /api/account/sub-account/{id}
     * @param nickname false string 子帳號暱稱
     * @param view_member_type int 0=全部會員，1=直客，2=代理會員
     * @param permissionids false string 1,2,3
     */
    public function update(SubEditRequest $request, $id)
    {
        $sub = SubAccount::findOrFail($id);

        $nickname = $request->nickname ?? '';
        if ($nickname) {
            $sub->user->nickname = $nickname;
        }

        $permissionIds = $request->input('permissionids');

        if ($permissionIds) {
            if (preg_match('/,/i', $permissionIds))
                $permissionIds = array_map('intval', explode(',', $permissionIds));
            else
                $permissionIds = (int) $permissionIds;

            $ids = $this->subAccountService->takeOffWriteWithGiveIds($permissionIds)->values();
            // 權限為空
            if ($ids->isEmpty())
                throw new ForbiddenException();

            $diffIds = $this->subAccountService->takeOffWithGiveIds($ids, $id, User::class)->values();

            $subUser = $this->subAccountService->getRoleOrUser($id, User::class);
            $subUser->revokePermissionTo($this->subAccountService->takeOffWithOriginIds($ids, $id, User::class));
            $subUser->givePermissionTo($diffIds);
        }

        $sub->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 變更密碼
     * @description 變更密碼
     * @method post
     * @url /api/account/sub-account/change-password
     * @param id true int 子帳號ID
     * @param password true string 密碼
     */
    public function changePassword(SubChangePasswordRequest $request)
    {
        $sub = SubAccount::findOrFail($request->id)->load('user');

        $sub->user->password = Hash::make($request->password);
        $sub->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 啟停用子帳號
     * @description 啟停用子帳號
     * @method post
     * @url /api/account/sub-account/toggle-enabled
     * @param id true int 子帳號ID
     * @param status true int 狀態，0=停用，1=啟用
     */
    public function toggleEnabled(SubToggleEnabledRequest $request)
    {
        $sub = SubAccount::findOrFail($request->id)->load('user');

        $sub->user->status = $request->status;
        $sub->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/後台子帳號相關API
     * @title 移除子帳號
     * @description 移除子帳號
     * @method delete
     * @url /api/account/sub-account/{id}
     */
    public function destroy($id)
    {
        $sub = SubAccount::findOrFail($id)->load('user');

        $subUser = $this->subAccountService->getRoleOrUser($id, User::class);
        $subUser->revokePermissionTo($subUser->Permissions);

        User::destroy($sub->user_id);
        SubAccount::destroy($id);
    }

    /**
     * showdoc
     * @catalog 帳號管理/後臺子帳號相關API
     * @title 登入記錄
     * @description 登入記錄
     * @method get
     * @url /api/account/sub-account/login-log/get
     * @param id true int 子帳號ID
     * @return {"data":[{"id":"1","ip":"127.0.0.1","device":"pc","browser":"IE","login_at":"2020-1-1 10:10:10"}]}
     * @return_param id int ID
     * @return_param ip string ip
     * @return_param device string 裝置
     * @return_param browser string 瀏覽器
     * @return_param login_at string 登入時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function loginLog(Request $request)
    {
        $sub = SubAccount::findOrFail($request->id);
        $res = (new LoginLogService())->userLoginLog($sub->user_id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/後臺子帳號相關API
     * @title 登入此IP使用者
     * @description 登入此IP使用者
     * @method get
     * @url /api/account/sub-account/same-ip-user-login/get
     * @param id true int 子帳號ID
     * @param ip true string IP
     * @return {"data":[{"id":"1","username":"agent01","device":"pc","browser":"IE","login_at":"2020-1-1 10:10:10"}]}
     * @return_param id int ID
     * @return_param username string 帳號
     * @return_param device string 裝置
     * @return_param browser string 瀏覽器
     * @return_param login_at string 登入時間
     */
    public function sameIpUserLogin(Request $request)
    {
        $sub = SubAccount::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpUserLogin($request->ip, $sub->user_id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/後臺子帳號相關API
     * @title 登入此IP會員
     * @description 登入此IP會員
     * @method get
     * @url /api/account/sub-account/same-ip-member-login/get
     * @param ip true string IP
     * @return {"data":[{"id":"1","username":"member01","device":"pc","browser":"IE","login_at":"2020-1-1 10:10:10"}]}
     * @return_param id int ID
     * @return_param username string 帳號
     * @return_param device string 裝置
     * @return_param browser string 瀏覽器
     * @return_param login_at string 登入時間
     */
    public function sameIpMemberLogin(Request $request)
    {
        $res = (new LoginLogService())->sameIpMemberLogin($request->ip);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/後臺子帳號相關API
     * @title 註冊此IP會員
     * @description 註冊此IP會員
     * @method get
     * @url /api/account/sub-account/same-ip-member-register/get
     * @param ip true string IP
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpMemberRegister(Request $request)
    {
        $res = (new LoginLogService())->sameIpMemberRegister($request->ip);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/後臺子帳號相關API
     * @title 特定會員及IP登入記錄
     * @description 特定會員及IP登入記錄
     * @method get
     * @url /api/account/sub-account/login-record-by-member-ip/get
     * @param id true int 會員ID
     * @param ip true string IP
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpSameMember(Request $request)
    {
        $res = (new LoginLogService())->sameIpMemberRegister($request->ip, $request->id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/後臺子帳號相關API
     * @title 特定帳號及IP登入記錄
     * @description 特定帳號及IP登入記錄
     * @method get
     * @url /api/account/sub-account/login-record-by-user-ip/get
     * @param id true int 控端ID
     * @param ip true string IP
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpSameUser(Request $request)
    {
        User::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpSameUser($request->ip, $request->id);
        return $this->returnData($res);
    }

    /**
     * @catalog 帳號管理/後台子帳號相關API
     * @title 取得所有帳號功能
     * @description 取得所有帳號功能
     * @method get
     * @url /api/account/sub-account/permission/get
     * @return {"data": [{"id": "1", "name": "Sub account managment(READ)"}]}
     * @return_param id int 功能ID
     * @return_param name string 功能名稱
     */
    public function permissionIndex()
    {
        return $this->returnData($this->subAccountService->getCreatePermissionTree());
    }
}
