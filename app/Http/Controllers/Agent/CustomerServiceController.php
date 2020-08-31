<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\CustomerService\CsAddRequest;
use App\Http\Requests\Account\CustomerService\CsChangePasswordRequest;
use App\Http\Requests\Account\CustomerService\CsEditRequest;
use App\Http\Requests\Account\CustomerService\CsQuotaRequest;
use App\Http\Requests\Account\CustomerService\CsToggleEnabledRequest;
use App\Models\CustomerService;
use App\Models\User;
use App\Service\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class CustomerServiceController extends Controller
{
    protected $logChannel = 'customerservicelog';

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 客服帳號列表
     * @description 客服帳號列表
     * @method get
     * @url /api/account/customer-service
     * @param username false string
     * @param nickname false string
     * @param level false int
     * @param status false int
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data": {"content":[{"id": "1", "username": "CS01", "nickname": "", "level": "1", "status": "1", "wallet": "100.00"}], "page": "1", "per_page": "15", "last_page": "5", "total": "150"}}
     * @return_param id int ID
     * @return_param username string 帳號
     * @return_param nickname string 暱稱
     * @return_param level int 層級，1=客服主管，2=客服人員
     * @return_param status int 0=停用，1=啟用
     * @return_param wallet string 額度
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function index(Request $request)
    {
        # auth
        $user = auth()->user();
        if ($user->isAgent() || ($user->isCustomerService() && $user->detail->level == 2)) {
            throw new ForbiddenException();
        }

        $query = CustomerService::with('user');

        if ($user->isCustomerService()) {
            $query->where('level', 2);
        }

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

        $level = $request->level ?? 'all';
        if ($level != 'all') {
            $query->where('level', $level);
        }

        $status = $request->status ?? 'all';
        if ($status != 'all') {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $perPage = intval($request->per_page ?? 15);

        $res = $query->paginate($perPage);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'username' => $row->user->username,
                    'nickname' => $row->user->nickname,
                    'level' => $row->level,
                    'status' => $row->user->status,
                    'wallet' => '',
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 取得所有帳號功能
     * @description 取得所有帳號功能
     * @method get
     * @url /api/account/customer-service/all-permission/get
     * @return {"data": [{"id": "1", "name": "Sub account managment(READ)"}]}
     * @return_param id int 功能ID
     * @return_param name string 功能名稱
     * @remark 擱置
     */
    public function allPermission(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 新增客服帳號
     * @description 新增客服帳號
     * @method post
     * @url /api/account/customer-service
     * @param username true string 帳號
     * @param password true string 密碼
     * @param nickname false string 暱稱
     * @param level true int 層級，1=客服主管，2=客服人員
     * @param permissions true array 帳號功能，["1","2","20"]
     */
    public function store(CsAddRequest $request)
    {
        // FIXME: 錢包、帳號功能擱置
        $user = auth()->user();
        if ($user->isAgent() || ($user->isCustomerService() && $user->detail->level == 2)) {
            throw new ForbiddenException();
        }

        DB::transaction(function () use ($request, $user) {
            $newUser = new User();
            $newUser->identity = 2;
            $newUser->username = $request->username;
            $newUser->password = Hash::make($request->password);
            $newUser->nickname = $request->nickname;
            $newUser->saveOrFail();

            $cs = new CustomerService();
            $cs->user_id = $newUser->id;
            $cs->level = $user->isAdmin() ? $request->level : 2;
            $cs->saveOrFail();
        });
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 客服帳號詳細資料
     * @description 客服帳號詳細資料
     * @method get
     * @url /api/account/customer-service/{id}
     * @return {"data": {"username": "GM01", "nickname": "", "level": "1", "created_at": "1010-10-10 10:10:10", "login_ip": "168.95.1.1", "login_at": "1010-10-10 10:10:10", "same_login_ip": "1", "operation_log": "1010-10-10 10:10:10", "permissions": ["1", "2", "5"]}}
     * @return_param username string 帳號
     * @return_param nickname string 暱稱
     * @return_param created_at string 創建時間
     * @return_param login_at string 最後登入時間
     * @return_param login_ip string 最後登入IP
     * @return_param same_login_ip int 登入IP是否重複，1=有，0=無
     * @return_param operation_at string 最後操作時間
     * @return_param permissions array 帳號功能
     * @remark 帳號功能擱置
     */
    public function show($id)
    {
        // FIXME: 帳號功能擱置
        $user = auth()->user();
        if ($user->isAgent() || ($user->isCustomerService() && $user->detail->level == 2)) {
            throw new ForbiddenException();
        }

        $cs = CustomerService::findOrFail($id);

        return $this->returnData([
            'username' => $cs->user->username,
            'nickname' => $cs->user->nickname,
            'level' => $cs->level,
            'created_at' => $cs->created_at,
            'login_at' => $cs->user->latestLoginLog->created_at ?? '',
            'login_ip' => $cs->user->latestLoginLog->ip ?? '',
            'same_login_ip' => $cs->user->ipExists(),
            'latest_operation_at' => '',
            'permissions' => [],
        ]);
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 編輯客服帳號
     * @description 編輯客服帳號
     * @method patch
     * @url /api/account/customer-service/{id}
     * @param id true int 客服ID
     * @param nickname true string 暱稱
     * @param permissions true array 帳號功能
     * @remark 帳號功能擱置
     */
    public function update(CsEditRequest $request, $id)
    {
        // FIXME: 帳號功能擱置
        $user = auth()->user();
        if ($user->isAgent() || ($user->isCustomerService() && $user->detail->level == 2)) {
            throw new ForbiddenException();
        }

        $cs = CustomerService::findOrFail($id);
        $nickname = $request->nickname ?? '';
        if ($nickname) {
            $cs->user->nickname = $nickname;
        }

        $cs->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 啟停用
     * @description 啟停用
     * @method post
     * @url /api/account/customer-service/toggle-enabled
     * @param id true int 子帳號ID
     * @param status true int 狀態，0=停用，1=啟用
     */
    public function toggleEnabled(CsToggleEnabledRequest $request)
    {
        $user = auth()->user();
        if ($user->isAgent() || ($user->isCustomerService() && $user->detail->level == 2)) {
            throw new ForbiddenException();
        }

        $cs = CustomerService::findOrFail($request->id)->load('user');
        $cs->status = $request->status;
        $cs->user->status = $request->status;
        $cs->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 變更密碼
     * @description 變更密碼
     * @method post
     * @url /api/account/customer-service/change-password
     * @param id true int 客服ID
     * @param password true string 密碼
     */
    public function changePassword(CsChangePasswordRequest $request)
    {
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $cs = CustomerService::findOrFail($request->id);
        $cs->user->password = Hash::make($request->password);
        $cs->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 額度
     * @description 額度
     * @method get
     * @url /api/account/customer-service/wallet/get
     * @param id true int 客服ID
     * @return {"data":{"amount":"100.00"}}
     * @return_param amount double 剩餘額度
     * @remark 擱置
     */
    public function wallet(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 收回額度
     * @description 收回額度
     * @method post
     * @url /api/account/customer-service/deduct-quota
     * @param id true int 客服ID
     * @param quota true double 額度
     * @param remark false string 備註
     * @remark 擱置
     */
    public function deductQuota(CsQuotaRequest $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 派發額度
     * @description 派發額度
     * @method post
     * @url /api/account/customer-service/add-quota
     * @param id true int 客服ID
     * @param quota true 額度
     * @remark 擱置
     */
    public function addQuota(CsQuotaRequest $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 派點紀錄
     * @description 派點紀錄
     * @method get
     * @url /api/account/customer-service/transfer-record/get
     * @param id true int 客服ID
     * @param start_at false string 起始時間
     * @param end_at false string 結束時間
     * @param type false string 類別
     * @param status false int 狀態
     * @param username false string 轉入帳號
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":{"content":[{"id":"1","type":"","transfer_in":"member01","amount":"100.00","status":"1","created_at":"1010-10-10 10:10:10","remark":""}],"page":"1","per_page":"15","last_page":"5","total":"150"}}
     * @return_param id int ID
     * @return_param type string 類別
     * @return_param transfer_in string 轉入帳號
     * @return_param amount double 額度
     * @return_param status int 狀態
     * @return_param created_at string 創建時間
     * @return_param remark string 備註
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     * @remark 擱置
     */
    public function transferRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 額度增減紀錄
     * @description 額度增減紀錄
     * @method get
     * @url /api/account/customer-service/quota-record/get
     * @param type false string
     * @param transfer_out false string
     * @param transfer_in false string
     * @param status false int
     * @param start_at false string
     * @param end_at false string
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data": {"content":[{"id":"1","type":"","transfer_out":"admin","transfer_in":"cs01","quota":"1000.00","status":"1","created_at":"1010-01-10 10:10:10","remark":""}],"page":"1","per_page":"15","last_page":"5","total":"150"}}
     * @return_param id int ID
     * @return_param type string 類別
     * @return_param transfer_out string 轉出帳號
     * @return_param transfer_in string 轉入帳號
     * @return_param quota double 額度
     * @return_param status int 0=停用，1=啟用
     * @return_param created_at string 日期
     * @return_param remark string備註
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     * @remark 擱置
     */
    public function quotaRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 登入記錄
     * @description 登入記錄
     * @method get
     * @url /api/account/customer-service/login-log/get
     * @param id true int 客服ID
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
        $cs = CustomerService::findOrFail($request->id);
        $res = (new LoginLogService())->userLoginLog($cs->user_id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 登入此IP使用者
     * @description 登入此IP使用者
     * @method get
     * @url /api/account/customer-service/same-ip-user-login/get
     * @param id true int 客服ID
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
        $cs = CustomerService::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpUserLogin($request->ip, $cs->user_id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 登入此IP會員
     * @description 登入此IP會員
     * @method get
     * @url /api/account/customer-service/same-ip-member-login/get
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
     * @catalog 帳號管理/客服帳號相關API
     * @title 註冊此IP會員
     * @description 註冊此IP會員
     * @method get
     * @url /api/account/customer-service/same-ip-member-register/get
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
     * @catalog 帳號管理/客服帳號相關API
     * @title 特定會員及IP登入記錄
     * @description 特定會員及IP登入記錄
     * @method get
     * @url /api/account/customer-service/login-record-by-member-ip/get
     * @param id true int 會員ID
     * @param ip true string IP
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpSameMember(Request $request)
    {
        $res = (new LoginLogService())->sameIpSameMember($request->ip, $request->id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/客服帳號相關API
     * @title 特定帳號及IP登入記錄
     * @description 特定帳號及IP登入記錄
     * @method get
     * @url /api/account/customer-service/login-record-by-user-ip/get
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
}
