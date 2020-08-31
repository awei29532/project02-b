<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Agent\AgentAddRequest;
use App\Http\Requests\Account\Agent\AgentChangePasswordRequest;
use App\Http\Requests\Account\Agent\AgentEditRequest;
use App\Http\Requests\Account\Agent\AgentGameCompanySettingRequest;
use App\Http\Requests\Account\Agent\AgentGameCompanyToggleEnabledRequest;
use App\Http\Requests\Account\Agent\AgentGameSettingRequest;
use App\Http\Requests\Account\Agent\AgentGameToggleEnabledRequest;
use App\Http\Requests\Account\Agent\AgentManualAddRequest;
use App\Http\Requests\Account\Agent\AgentManualDeductRequest;
use App\Http\Requests\Account\Agent\AgentToggleEnabledRequest;
use App\Models\Agent;
use App\Models\User;
use App\Models\AgentGameCompanyConfig;
use App\Models\AgentGameConfig;
use App\Models\AgentWallet;
use App\Models\Game;
use App\Models\GameCompany;
use App\Models\Rebate;
use App\Models\UserLoginLog;
use App\Service\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class AgentController extends Controller
{
    protected $logChannel = 'agentlog';

    public function index(Request $request)
    {
        /** @var User */
        $user = auth()->user();
        $parent_id = intval($request->parent_id ?? 0);
        $parent_id = $user->allowAgentId($parent_id) ? $parent_id : $user->detail->id;
        $username = $request->username ?? '';
        $nickname = $request->nickname ?? '';
        $upper_agent = $request->upper_agent ?? '';
        $status = $request->status ?? 'all';
        $per_page = intval($request->per_page ?? 15);

        $query = Agent::select('*');

        if ($parent_id) {
            $query->where('parent_id', $parent_id);
        } else {
            $query->whereNull('parent_id');
        }

        if ($user->isAgent()) {
            $query->whereIsAfter($user->detail->id);
        }

        if ($username) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->where('username', 'like', "%$username%");
            });
        }

        if ($nickname) {
            $query->whereHas('user', function ($q) use ($nickname) {
                $q->where('nickname', 'like', "%$nickname%");
            });
        }

        if ($status != 'all') {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                $data = [
                    'id' => $row->id,
                    'username' => $row->user->username,
                    'nickname' => $row->user->nickname,
                    'status' => $row->user->status,
                    'level' => $row->level,
                    'remark' => $row->remark,
                    'owner' => $row->owner()->user->username ?? 'owner',
                    'parent' => $row->parent->user->username ?? 'owner',
                ];

                if ($row->parent_id) {
                    $data['wallet'] = $row->wallet->amount;
                }

                return $data;
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 代理詳細資料
     * @description 代理詳細資料
     * @method get
     * @url /api/account/agent/{id}
     * @param id true int 代理ID
     * @return {"data":{"id":"1","username":"admin","nickname":"god","level":"0","parent":"owner","owner":"owner","cell_phone":"","remark":"", "login_at": "1010-10-10 10:10:10", "login_ip":"168.95.1.1","same_login_ip":"1"}}
     * @return_param id int 代理ID
     * @return_param username string 代理帳號
     * @return_param nickname string 代理暱稱
     * @return_param level int 代理層級
     * @return_param parent string 上層代理
     * @return_param owner string 大股東線
     * @return_param cell_phone string 手機號碼
     * @return_param remark string 備註
     * @return_param login_at string 最後登入時間
     * @return_param same_login_ip int 登入IP是否重複，1=有，0=無
     */
    public function show($id)
    {
        /** @var User */
        $user = auth()->user();

        if (!$user->allowAgentId($id)) {
            throw new ForbiddenException();
        }

        $agent = Agent::findOrFail($id);

        return $this->returnData([
            'id' => $agent->id,
            'username' => $agent->user->username,
            'nickname' => $agent->user->nickname,
            'level' => $agent->level,
            'parent' => $agent->parent->user->username ?? 'owner',
            'cell_phone' => $agent->cell_phone,
            'remark' => $agent->remark,
            'login_at' => $agent->user->latestLoginLog->created_at ?? '',
            'same_login_ip' => $agent->user->ipExists(),
        ]);
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 新增代理
     * @description 新增代理帳號
     * @method post
     * @url /api/account/agent
     * @param parent_id false int 上層代理ID
     * @param username true string 代理帳號(4~8碼)
     * @param password true string 密碼(6~10碼)
     * @param nickname false string 代理暱稱(最大值16碼)
     * @param cell_phone false string 手機號碼
     * @param remark false string 備註
     */
    public function store(AgentAddRequest $request)
    {
        /** @var User */
        $user = auth()->user();
        $parent_id = $request->parent_id ?? 0;

        if ($user->isAgent() && !$user->isSubAccount()) {
            $parent = $user->detail;
        } elseif ($user->isAdmin()) {
            $parent = $parent_id ? Agent::findOrFail($parent_id) : null;
        } else {
            throw new ForbiddenException();
        }

        DB::transaction(function () use ($request, $parent) {
            $newUser = new User();
            $newUser->identity = 3;
            $newUser->username = $request->username;
            $newUser->password = Hash::make($request->password);
            $newUser->saveOrFail();

            $agent = new Agent();
            $agent->user_id = $newUser->id;
            $agent->level = $parent ? ($parent->level + 1) : 1;
            $agent->cell_phone = $request->cell_phone ?? null;
            $agent->remark = $request->remark ?? null;
            $agent->generatorInvitationCode($newUser->username);

            if ($agent->level > 1) {
                $agent->appendToNode($parent)->save();
                $newUser->assignRole('agent');
            } else {
                $agent->saveAsRoot();
                $newUser->assignRole('owner');
            }

            $agent_wallet = new AgentWallet();
            $agent_wallet->agent_id = $agent->id;
            $agent_wallet->saveOrFail();
        });
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 編輯代理
     * @description 編輯代理
     * @method patch
     * @url /api/account/agent/{id}
     * @param id true int 代理ID
     * @param nickname false string 代理暱稱
     * @param cell_phone false string 手機號碼
     * @param remark false string 備註
     */
    public function update(AgentEditRequest $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $nickname = $request->nickname ?? '';
        if ($nickname) {
            $agent->user->nickname = $nickname;
        }

        $cell_phone = $request->cell_phone ?? '';
        if ($cell_phone) {
            $agent->cell_phone = $cell_phone;
        }

        $remark = $request->remark ?? '';
        if ($remark) {
            $agent->remark = $remark;
        }

        $agent->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 啟停用代理
     * @description 啟停用代理
     * @method post
     * @url /api/account/agent/toggle-enabled
     * @param id true int 代理ID
     * @param status true int 帳號狀態，1=啟用，0=停用
     */
    public function toggleEnabled(AgentToggleEnabledRequest $request)
    {
        $agent = Agent::findOrFail($request->id);
        $agent->user->status = $request->status;
        $agent->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 變更密碼
     * @description 變更密碼
     * @method post
     * @url /api/account/agent/change-password
     * @param id true int 代理ID
     * @param password true string 密碼
     */
    public function changePassword(AgentChangePasswordRequest $request)
    {
        /** @var User */
        $user = auth()->user();

        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $agent = Agent::findOrFail($request->id);
        $agent->user->password = Hash::make($request->password);
        $agent->push();
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 遊戲商設定
     * @description 取得所有遊戲商設定
     * @method get
     * @url /api/account/agent/game-company-setting/get
     * @param id true int 代理ID
     * @return {"data":[{"company_id":"1","name":"asdgf","status":"1"}]}
     * @return_param company_id int 遊戲商ID
     * @return_param status int 狀態，1=啟用，0=停用
     */
    public function gameCompanySetting(AgentGameCompanySettingRequest $request)
    {
        /** @var User */
        $user = auth()->user();
        $id = intval($request->id);
        if (!$user->allowAgentId($id)) {
            throw new ForbiddenException();
        }

        $res = AgentGameCompanyConfig::where('agent_id', $id)->get();
        if (!count($res) && $id) {
            $insertData = [];
            GameCompany::get()->each(function ($item) use (&$insertData, $id) {
                array_push($insertData, [
                    'agent_id' => $id,
                    'company_id' => $item->id,
                ]);
            });
            AgentGameCompanyConfig::insert($insertData);
            $res = AgentGameCompanyConfig::where('agent_id', $id)->get();
        }

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'company_id' => $row->company_id,
                    'name' => $row->company->name,
                    'status' => $row->status,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 啟停用遊戲商
     * @description 啟停用遊戲商
     * @method post
     * @url /api/account/agent/game-company/toggle-enabled
     * @param agent_id true int 代理ID
     * @param company_id true int 遊戲商ID
     * @param status true int 狀態，1=啟用，0=停用
     */
    public function gameCompanyToggleEnabled(AgentGameCompanyToggleEnabledRequest $request)
    {
        $agent_id = $request->agent_id;
        $company_id = $request->company_id;

        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $agent = Agent::find($agent_id);
        if (!$agent->isOwner()) {
            $upperStatus = AgentGameCompanyConfig::where('agent_id', $agent_id)
                ->where('company_id', $company_id)
                ->first()
                ->status ?? null;
            if (!$upperStatus) {
                throw new BadRequestException(trans('common.upper_status_error'), $this->logChannel);
            }
        }

        AgentGameCompanyConfig::where('agent_id', $request->agent_id)
            ->where('company_id', $request->company_id)
            ->update(['status' => "$request->status"]);
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 取得遊戲設定
     * @description 取得遊戲設定
     * @method get
     * @url /api/account/agent/game-setting/get
     * @param agent_id true int 代理ID
     * @param company_id true int 遊戲商ID
     * @return {"data":[{"game_id":"1","name":"slot","status":"1"}]}
     * @return_param id int 遊戲ID
     * @return_param name string 遊戲名稱
     * @return_param status int 狀態，0=停用，1=啟用
     */
    public function gameSetting(AgentGameSettingRequest $request)
    {
        $res = AgentGameConfig::where('agent_id', $request->agent_id)
            ->whereIn('game_id', function ($q) use ($request) {
                $q->select('id')
                    ->from('game')
                    ->where('company_id', $request->company_id)
                    ->get();
            })->get();

        if (!count($res)) {
            $insertData = [];
            Game::where('company_id', $request->company_id)
                ->get()
                ->each(function ($item) use (&$insertData, $request) {
                    array_push($insertData, [
                        'agent_id' => $request->agent_id,
                        'game_id' => $item->id,
                    ]);
                });
            AgentGameConfig::insert($insertData);
            $res = AgentGameConfig::where('agent_id', $request->agent_id)
                ->whereIn('game_id', function ($q) use ($request) {
                    $q->select('id')
                        ->from('game')
                        ->where('company_id', $request->company_id)
                        ->get();
                })->get();
        }

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'id' => $row->game_id,
                    'name' => $row->game->name,
                    'status' => $row->status,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 啟停用遊戲
     * @description 啟停用遊戲
     * @method post
     * @url /api/account/agent/game/toggle-enabled
     * @param agent_id true int 代理ID
     * @param game_id true int 遊戲ID'
     * @param status true int 狀態，1=啟用，0=停用
     */
    public function gameToggleEnabled(AgentGameToggleEnabledRequest $request)
    {
        $agent_id = $request->agent_id;
        $game_id = $request->game_id;

        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $agent = Agent::find($agent_id);
        if (!$agent->isOwner()) {
            $upperStatus = AgentGameConfig::where('agent_id', $agent_id)
                ->where('game_id', $game_id)
                ->first()
                ->status ?? null;
            if (!$upperStatus) {
                throw new BadRequestException(trans('common.upper_status_error'), $this->logChannel);
            }
        }

        AgentGameConfig::where('agent_id', $request->agent_id)
            ->where('game_id', $request->game_id)
            ->update(['status' => "$request->status"]);
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 代理錢包
     * @description 代理錢包
     * @method get
     * @url /api/account/agent/wallet/get
     * @param id true int 代理ID
     * @return {"data": {"amount": "500.00"}}
     * @return_param amount string 金額
     */
    public function wallet(Request $request)
    {
        $wallet = AgentWallet::findOrFail($request->id);

        return $this->returnData([
            'amount' => $wallet->amount,
        ]);
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 手動扣點
     * @description 手動扣點
     * @method post
     * @url /api/account/agent/manual-deduct
     * @param id true int 代理ID
     * @param amount true double 金額
     * @param remark false string 備註
     */
    public function manualDeduct(AgentManualDeductRequest $request)
    {
        // FIXME: 記錄擱置
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $wallet = AgentWallet::find($request->id);
        $wallet->amount -= $request->amount;
        $wallet->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 手動加點
     * @description 手動加點
     * @method post
     * @url /api/account/agent/manual-add
     * @param id true int 代理ID
     * @param amount true double 金額
     * @param remark false string 備註
     */
    public function manualAdd(AgentManualAddRequest $request)
    {
        // FIXME: 記錄擱置
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $wallet = AgentWallet::findOrFail($request->id);
        $wallet->amount += $request->amount;
        $wallet->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 轉點紀錄
     * @description 轉點紀錄
     * @method get
     * @url /api/account/agent/transfer-record/get
     * @param started_at false string 起始時間
     * @param finished_at false string 結束時間
     * @param type false string 類別
     * @return {"data": {"content":[{"id": "1", "type": "", "transfer_out": "guest01", "transfer_in": "guest02", "amount": "10.00", "status": "1", "transfer_at": "1010-10-10 10:10:10", "remark": "123321"}], , "page": "1", "per_page": "15", "last_page": "5", "total": "150"}}
     * @return_param id int 報表ID
     * @return_param type string 類別
     * @return_param transfer_out string 轉出者
     * @return_param transfer_in string 轉入者
     * @return_param amount string 交易點數
     * @return_param status string 狀態
     * @return_param transfer_at string 交易日期
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
     * @catalog 帳號管理/代理相關API
     * @title 取得代理儲值方式
     * @description 取得代理儲值方式
     * @method get
     * @url /api/account/agent/all-deposit-method/get
     * @param agent_id true int 代理ID
     * @return {"data": {"convenience_store": "1", "credit_card": "0", "atm": "1", "bank": [{"bank_name": "臺灣銀行", "account": "44445555", "line": "88888"}]}}
     * @return_param convenience_store int 超商
     * @return_param credit_card int 信用卡
     * @return_param atm int ATM
     * @return_param bank_name string 銀行名稱
     * @return_param bank_account string 銀行帳號
     * @return_param line string LINE ID
     * @remark 擱置
     */
    public function allDepositMethod(Request $request)
    {
        // TODO:
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 變更存款方式
     * @description 變更存款方式
     * @method post
     * @url /api/account/agent/update-deposit-method
     * @param agent_id true int 代理ID
     * @param convenience_stroe true int 超商
     * @param credit_card true int 信用卡
     * @param atm true int ATM
     * @param bank true array 銀行
     * @remark bank欄位格式 [["bank_id": "1", "status": "1"], ...]
     */
    public function updateDepositMethod(Request $request)
    {
        // TODO:
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 取得所有代理
     * @description 取得所有代理
     * @method get
     * @url /api/account/agent/search-agent/get
     * @param name true string 代理帳號、暱稱
     * @return {"data": [{"id": "1", "username": "agent01", "nickname": "agent01"}]}
     * @return_param id int 代理ID
     * @return_param username string 代理帳號
     * @return_param nickname string 代理暱稱
     */
    public function searchAgent(Request $request)
    {
        /** @var User */
        $user = auth()->user();

        $query = Agent::whereHas('user', function ($q) use ($request) {
            $q->where('username', 'like', "%$request->name%")
                ->orWhere('nickname', 'like', "%$request->name%");
        });

        if ($user->isAgent()) {
            $query->whereIsAfter($user->detail->id);
        }

        $res = $query->get();

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'username' => $row->user->username,
                    'nickname' => $row->user->nickname
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 取得所有返水等級
     * @description 取得所有返水等級
     * @method get
     * @url /api/account/agent/all-rebate/get
     * @return {"data": {["id": "1", "name": "DG rebate"]}}
     * @return_param id int 返水ID
     * @return_param name string 返水名稱
     */
    public function allRebate(Request $request)
    {
        $res = Rebate::get();

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 登入記錄
     * @description 登入記錄
     * @method get
     * @url /api/account/agent/login-log/get
     * @param id true int 代理ID
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
        /** @var User */
        $user = auth()->user();
        if (!$user->allowAgentId($request->id)) {
            throw new ForbiddenException();
        }
        $agent = Agent::findOrFail($request->id);
        $res = (new LoginLogService())->userLoginLog($agent->user_id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/代理相關API
     * @title 登入此IP使用者
     * @description 登入此IP使用者
     * @method get
     * @url /api/account/agent/same-ip-user-login/get
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
     * @catalog 帳號管理/代理相關API
     * @title 登入此IP會員
     * @description 登入此IP會員
     * @method get
     * @url /api/account/agent/same-ip-member-login/get
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
     * @catalog 帳號管理/代理相關API
     * @title 註冊此IP會員
     * @description 註冊此IP會員
     * @method get
     * @url /api/account/agent/same-ip-member-register/get
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
     * @catalog 帳號管理/代理相關API
     * @title 特定會員及IP登入記錄
     * @description 特定會員及IP登入記錄
     * @method get
     * @url /api/account/agent/login-record-by-member-ip/get
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
     * @catalog 帳號管理/代理相關API
     * @title 特定帳號及IP登入記錄
     * @description 特定帳號及IP登入記錄
     * @method get
     * @url /api/account/agent/login-record-by-user-ip/get
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
