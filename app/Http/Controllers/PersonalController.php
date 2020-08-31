<?php

namespace App\Http\Controllers;

use App\Http\Requests\personal\PersonalChangePasswordRequest;
use App\Http\Requests\personal\PersonalUpdateRequest;
use App\Models\UserLoginLog;
use App\Service\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PersonalController extends Controller
{
    protected $user;

    protected $logChannel = 'personallog';

    function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 取得個人資料
     * @description 取得個人資料
     * @method get
     * @url /api/personal
     * @return {"data":{"username":"admin","nickname":"god","identity":"admin","created_at":"1010-10-10 10:10:10","login_at":"1010-10-10 10:10:10","same_login_ip":"0","image":""}}
     * @return_param username string 帳號
     * @return_param nickname string 暱稱
     * @return_param identity string 身分
     * @return_param created_at string 創建時間
     * @return_param login_at string 最後登入時間
     * @return_param same_login_ip int 相同登入IP
     * @return_param image string 頭像URL
     */
    public function index()
    {
        return $this->returnData([
            'username' => $this->user->username,
            'nickname' => $this->user->nickname ?? '',
            'identity' => $this->user->identity,
            'created_at' => $this->user->created_at,
            'login_at' => $this->user->latestLoginLog->created_at ?? '',
            'login_ip' => $this->user->latestLoginLog->ip,
            'same_login_ip' => $this->user->ipExists(),
            'image' => $this->user->image ? Storage::url($this->user->image) : null,
        ]);
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 編輯個人資料
     * @description 編輯個人資料
     * @method patch
     * @url /api/personal/update
     * @param nickname false string 暱稱
     * @param image false image 頭像
     */
    public function update(PersonalUpdateRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = Storage::disk('storage-public')->put('upload/image', $request->image);
            $this->user->image = $image;
        }
        $this->user->nickname = $request->nickname;
        $this->user->saveOrFail();

        return $this->returnData([
            'image' => $this->user->image ? Storage::url($this->user->image) : null
        ]);
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 變更密碼
     * @description 變更密碼
     * @method post
     * @url /api/personal/change-password
     * @param password true string 新密碼
     */
    public function changePassword(PersonalChangePasswordRequest $request)
    {
        $this->user->password = Hash::make($request->password);
        $this->user->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 轉點紀錄
     * @description 轉點紀錄
     * @method get
     * @url /api/personal/transfer-record
     * @param type false string 類別
     * @param status false int 狀態
     * @param start_at false string 開始時間
     * @param end_at false string 結束時間
     * @return {"data":{"total_trans_out":"123","total_trans_in":"123","content":[{"type":"","transfer_from":"admin","transfer_to":"agent01","amount":"123","status":"1","remark":"安安","created_at":"1010-10-10 10:10:01"}],"page":"1","per_page":"15","last_page":"5","total": "150"}}
     * @return_param total_trans_in double 總轉出點數
     * @return_param total_trans_out double 總轉入點數
     * @return_param type string 類別
     * @return_param transfer_from string 轉出者
     * @return_param transfer_to string 轉入者
     * @return_param amount double 點數
     * @return_param status int 狀態，0=失敗，1=成功
     * @return_param remark string 註解
     * @return_param created_at string 創建時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function transferRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 額度增減紀錄
     * @description 額度增減紀錄
     * @method get
     * @url /api/personal/quota-record
     * @param type false string 類別
     * @param status false int 狀態
     * @param start_at false string 開始時間
     * @param end_at false string 結束時間
     * @return {"data":{"content":[{"type":"","transfer_from":"admin","transfer_to":"agent01","quota":"100","remark":"asd","created_at":"1010-10-10 10:10:10"}],"page":"1","per_page":"15","last_page":"5","total": "150"}}
     * @return_param type string 類別
     * @return_param transfer_from string 轉出者
     * @return_param transfer_to string 轉入者
     * @return_param quota double 額度
     * @return_param status int 狀態
     * @return_param remark string 註解
     * @return_param created_at string 創建時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function quotaRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 登入記錄
     * @description 登入記錄
     * @method get
     * @url /api/personal/login-log/get
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
    public function loginLog()
    {
        $res = (new LoginLogService())->userLoginLog($this->user->id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 登入此IP使用者
     * @description 登入此IP使用者
     * @method get
     * @url /api/personal/same-ip-user-login/get
     * @param id true int 資料ID
     * @return {"data":[{"id":"1","username":"agent01","device":"pc","browser":"IE","login_at":"2020-1-1 10:10:10"}]}
     * @return_param id int ID
     * @return_param username string 帳號
     * @return_param device string 裝置
     * @return_param browser string 瀏覽器
     * @return_param login_at string 登入時間
     */
    public function sameIpUserLogin(Request $request)
    {
        $log = UserLoginLog::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpUserLogin($log->ip, $log->user_id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 登入此IP會員
     * @description 登入此IP會員
     * @method get
     * @url /api/personal/same-ip-member-login/get
     * @param id true int 資料ID
     * @return {"data":[{"id":"1","username":"member01","device":"pc","browser":"IE","login_at":"2020-1-1 10:10:10"}]}
     * @return_param id int ID
     * @return_param member_id int 會員ID
     * @return_param username string 帳號
     * @return_param device string 裝置
     * @return_param browser string 瀏覽器
     * @return_param login_at string 登入時間
     */
    public function sameIpMemberLogin(Request $request)
    {
        $log = UserLoginLog::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpMemberLogin($log->ip);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 註冊此IP會員
     * @description 註冊此IP會員
     * @method get
     * @url /api/personal/same-ip-member-register/get
     * @param id true int 資料ID
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpMemberRegister(Request $request)
    {
        $log = UserLoginLog::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpMemberRegister($log->ip);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 個人設置
     * @title 特定會員及IP登入記錄
     * @description 特定會員及IP登入記錄
     * @method get
     * @url /api/personal/login-record-by-member-ip/get
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
     * @catalog 個人設置
     * @title 特定帳號及IP登入記錄
     * @description 特定帳號及IP登入記錄
     * @method get
     * @url /api/personal/login-record-by-user-ip/get
     * @param id true int 控端ID
     * @param ip true string IP
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpSameUser(Request $request)
    {
        $res = (new LoginLogService())->sameIpSameUser($request->ip, $request->id);
        return $this->returnData($res);
    }
}
