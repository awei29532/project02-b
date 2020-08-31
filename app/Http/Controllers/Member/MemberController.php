<?php

namespace App\Http\Controllers\Member;

use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Member\MemberAddRequest;
use App\Http\Requests\Account\Member\MemberChangePasswordRequest;
use App\Http\Requests\Account\Member\MemberEditRequest;
use App\Http\Requests\Account\Member\MemberGameCompanySettingRequest;
use App\Http\Requests\Account\Member\MemberGameCompanyToggleEnabledRequest;
use App\Http\Requests\Account\Member\MemberGameSettingRequest;
use App\Http\Requests\Account\Member\MemberGameToggleEnabledRequest;
use App\Http\Requests\Account\Member\MemberManualAddRequest;
use App\Http\Requests\Account\Member\MemberManualDeductRequest;
use App\Http\Requests\Account\Member\MemberToggleEnabledRequest;
use App\Models\MemberGameWallet;
use App\Models\Agent;
use App\Models\Game;
use App\Models\GameCompany;
use App\Models\Level;
use App\Models\Member;
use App\Models\MemberGameCompanyConfig;
use App\Models\MemberGameConfig;
use App\Models\MemberLoginLog;
use App\Models\MemberWallet;
use App\Models\Rebate;
use App\Models\SiteConfig;
use App\Models\User;
use App\Service\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    protected $logChannel = 'memberlog';

    public function __construct()
    {
        $this->can('member_member')->only('index');
        $this->can('member_member_search')->only('show');
        $this->can('member_member_add')->only('store');
        $this->can('member_member_status')->only('toggleEnabled');
    }

    public function index(Request $request)
    {
        $agent_id = intval($request->agent_id ?? 0);
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            if ($user->detail->isOwner()) {
                $agent_id = $user->detail->allowAgentId($agent_id) ? $agent_id : $user->detail->id;
            } else {
                $agent_id = $user->detail->id;
            }
        }

        $query = Member::where('agent_id', $agent_id)->where('status', '1');

        $username = $request->username ?? '';
        if ($username) {
            $query->where('username', '$username');
        }

        $invitation_code = $request->invitation_code ?? '';
        if ($invitation_code) {
            $query->where('bind_code', $invitation_code)->orWhere('invitation_code', $invitation_code);
        }

        $per_page = intval($request->per_page ?? 15);
        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'username' => $row->username,
                    'level' => $row->level->name,
                    'agent' => $row->agent->user->username ?? 'owner',
                    'bind_code' => $row->bind_code,
                    'invitation_code' => $row->invitation_code,
                    'status' => $row->status,
                    'balance' => number_format($row->wallet->amount, 2),
                    'freeze_amount' => number_format($row->wallet->freeze_amount, 2),
                    'login_ip' => $row->latestLoginLog->ip ?? '',
                    'register_ip' => $row->register_ip,
                    'register_at' => $row->created_at,
                ];
            }),
            $res
        );
    }

    public function ipExistsList(Request $request)
    {
        $agent_id = intval($request->agent_id ?? 0);
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            if ($user->detail->isOwner()) {
                $agent_id = $user->detail->allowAgentId($agent_id) ? $agent_id : $user->detail->id;
            } else {
                $agent_id = $user->detail->id;
            }
        }

        $query = Member::where('agent_id', $agent_id);

        $username = $request->username ?? '';
        if ($username) {
            $query->where('username', '$username');
        }

        $invitation_code = $request->invitation_code ?? '';
        if ($invitation_code) {
            $query->where('bind_code', $invitation_code)->orWhere('invitation_code', $invitation_code);
        }

        $per_page = intval($request->per_page ?? 15);
        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'login_ip_exists' => $row->loginIpExists(),
                    'register_ip_exists' => $row->registerIpExists(),
                ];
            }),
            $res
        );
    }

    public function store(MemberAddRequest $request)
    {
        // FIXME: 尚缺遊戲平台註冊、遊戲子錢包

        /** @var User */
        $user = auth()->user();
        $agent_id = intval($request->agent_id ?? 0);

        if ($user->isAgent()) {
            $agent_id = $user->detail->id;
        }

        $member = new Member();
        $member->agent_id = $agent_id;
        $member->username = $request->username;
        $member->generatePassword();
        $member->nickname = $request->nickname ?? null;
        $member->level_id = $request->level_id ?? 0;
        $member->rebate_id = $request->rebate_id ?? Rebate::where('preset', '1')->first()->id;
        $member->cell_phone = $request->cell_phone ?? null;
        $member->birthday = $request->birthday ?? null;
        $member->country = $request->country ?? null;
        $member->email = $request->email ?? null;
        $member->address = $request->address ?? null;
        $member->line = $request->line ?? null;
        $member->register_ip = $request->ip();
        $member->generatorInvitationCode();
        $member->saveOrFail();

        $wallet = new MemberWallet();
        $wallet->member_id = $member->id;
        $wallet->amount = 0;
        $wallet->saveOrFail();
    }

    public function searchAgent(Request $request)
    {
        $query = Agent::whereHas('user', function ($q) use ($request) {
            $name = $request->name ? "%$request->name%" : '';
            $q->where('username', 'like', $name)
                ->orWhere('nickname', 'like', $name);
        });

        /** @var User */
        $user = auth()->user();
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

    public function toggleEnabled(MemberToggleEnabledRequest $request)
    {
        $member = Member::findOrFail($request->id);
        $member->remark = $request->remark ?? null;

        if ($request->status != 2) {
            $member->status = $request->status;
            $member->saveOrFail();
        } else {
            $member->delete();
        }
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);

        # auth
        /** @var User */
        $user = auth()->user();
        if (!$user->allowAgentId($member->agent_id)) {
            throw new ForbiddenException();
        }

        # member company wallet
        $config = SiteConfig::where('site_id', 1)
            ->where('name', SiteConfig::site_menu)
            ->first();
        $member_game_wallet = MemberGameWallet::where('member_id', $id)->get();

        $wallets = [];
        foreach (json_decode($config->value, true) as $value) {
            if (!$value['status']) {
                continue;
            }

            foreach ($value['companies'] as $company) {
                if (!$company['status']) {
                    continue;
                }

                $wallets[$company['key']] = [
                    'name' => $company['name'],
                    'amount' => '-',
                ];
            }
        }

        $sub_wallet = 0;
        $member_game_wallet->each(function ($item, $key) use (&$wallets, &$sub_wallet) {
            $wallets[$item->company->key]['amount'] = number_format($item->amount, 2);
            $sub_wallet += $item->amount;
        });

        return $this->returnData(
            [
                'agent' => $member->agent->user->username,
                'username' => $member->username,
                'nickname' => $member->nickname,
                'level_id' => $member->level->id,
                'cell_phone' => $member->cell_phone,
                'birthday' => $member->birthday,
                'address' => $member->address,
                'line' => $member->line,
                'login_at' => $member->latestLoginLog->created_at ?? '',
                'login_ip' => $member->latestLoginLog->ip ?? '',
                // 'same_login_ip' => $member->loginIpExists(),
                'register_at' => $member->created_at,
                // 'register_ip' => $member->register_ip,
                // 'same_register_ip' => $member->registerIpExists(),
                // 'remark' => $member->remark,
                'balance' => [
                    'total_balance' => number_format($member->wallet->amount + $sub_wallet, 2),
                    'main_wallet' => number_format($member->wallet->amount, 2),
                    'sub_wallet' => number_format($sub_wallet, 2),
                    'freeze_amount' => number_format($member->wallet->freeze_amount, 2),
                    'game_wallets' => array_values($wallets),
                ],
            ]
        );
    }

    public function update(MemberEditRequest $request, $id)
    {
        $member = Member::findOrFail($id);

        # auth
        /** @var User */
        $user = auth()->user();
        if (!$user->allowAgentId($member->agent_id)) {
            throw new ForbiddenException();
        }

        $member->nickname = $request->nickname ?? null;
        $member->level_id = $request->level_id ?? 0;
        $member->cell_phone = $request->cell_phone ?? null;
        $member->email = $request->email ?? null;
        $member->address = $request->address ?? null;
        $member->line = $request->line ?? null;
        $member->saveOrFail();
    }

    public function changePassword(MemberChangePasswordRequest $request)
    {
        /** @var Member */
        $member = Member::findOrFail($request->id);
        $password = $member->generatePassword();
        $member->saveOrFail();

        return $this->returnData([
            'password' => $password,
        ]);
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 手動扣點
     * @description 手動扣點
     * @method post
     * @url /api/account/member/manual-deduct
     * @param id true int 會員ID
     * @param amount true double 金額
     * @param remark false string 備註
     */
    public function manualDeduct(MemberManualDeductRequest $request)
    {
        // FIXME: 紀錄擱置
        # auth
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }
        $wallet = Member::findOrFail($request->id)->wallet;
        $wallet->amount -= $request->amount;

        if ($wallet->amount < 0) {
            throw new BadRequestException(trans('common.insufficient_balance'), $this->logChannel);
        }

        $wallet->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 手動加點
     * @description 手動加點
     * @method post
     * @url /api/account/member/manual-add
     * @param id true int 會員ID
     * @param type true string 類別
     * @param amount true double 金額
     * @param bet_multiple true string 流水倍數
     * @param wagering_req true string 流水要求
     * @param remark false string 備註
     */
    public function manualAdd(MemberManualAddRequest $request)
    {
        // FIXME: 紀錄、流水、代理 客服派彩擱置
        # auth
        $member = Member::findOrFail($request->id);
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent() && $user->detail->isOwner() || !$user->allowAgentId($member->agent_id)) {
            throw new ForbiddenException();
        }

        $wallet = MemberWallet::find($request->id);
        $wallet->amount += $request->amount;
        $wallet->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 銀行轉帳
     * @description 銀行轉帳
     * @method post
     * @url /api/account/member/bank-transfer
     * @param id true int
     * @param transfer_out_account true string
     * @param transfer_in_account true string
     * @param amount true double 金額
     * @param bet_multiple true int 流水倍數
     * @param wagering_req true double 流水要求
     * @param transfer_at true string 交易時間
     * @param transfer_number false string 匯款編號
     * @param remark false string 備註
     * @param detail_img false img 細節照片
     * @reamrk 擱置
     */
    public function bankTransfer(Request $request)
    {
        // TODO:
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }
    }

    public function memberLevel(Request $request)
    {
        $res = Level::get();

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
     * @catalog 帳號管理/會員相關API
     * @title 取得所有返水等級
     * @description 取得所有返水等級
     * @method get
     * @url /api/account/member/all-rebate/get
     * @return {"data": [{"id":"1","name":"DG rebate"}]}
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
                    'type' => $row->type,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 儲值紀錄
     * @description 儲值紀錄
     * @method get
     * @url /api/account/member/deposit-record/get
     * @param started_at false string 起始時間
     * @param finished_at false string 結束時間
     * @param type false string 類別
     * @param status false int 狀態，1=成功，0=失敗
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":{"content":[{"id":"1","type":"atm","amount":"100.00","transfer_at":"1010-10-10 10:10:10","status":"1","order_id":"123","outside_order_id":"qaz123"}], "page":"1","per_page":"15","last_page":"5","total":"150"}}
     * @return_param id int 紀錄ID
     * @return_param type string 類別
     * @return_param amount string 金額
     * @return_param transfer_at string 交易時間
     * @return_param status int 狀態
     * @return_param order_id string 訂單編號
     * @return_param outside_order_id string 外部訂單編號
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     * @remark type待確認
     */
    public function depositRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 轉點紀錄
     * @description 轉點紀錄
     * @method get
     * @url /api/account/member/transfer-record/get
     * @param started_at false string 起始時間
     * @param finished_at false string 結束時間
     * @param type false string 類別
     * @param status false int 狀態
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":{"content":[{"id":"1","type":"offer bonus","transfer_out":"platform","transfer_in":"main","amount":"100.00","status":"1","transfer_at":"1010-10-10 10:10:10","remark":""}], "page":"1","per_page":"15","last_page":"5","total":"150"}}
     * @return_param id int 紀錄ID
     * @return_param type string 類別
     * @return_param transfer_out string 轉出
     * @return_param transfer_in string 轉入
     * @return_param amount string 金額
     * @return_param status int 狀態
     * @return_param transfer_at string 交易時間
     * @return_param remark string 備註
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     * @remark type待確認
     */
    public function transferRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 總輸贏紀錄
     * @description 總輸贏紀錄
     * @method get
     * @url /api/account/member/win-loss-record/get
     * @param game_company_id false int 遊戲商ID
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":{"content":[{"id":"1","bet_amount":"10.00","valid_amount":"10.00","win_loss_amount":"10.00","bet_num":"5","valid_bet_num":"5"}], "page":"1","per_page":"15","last_page":"5","total":"150"}}
     * @return_param id int 
     * @return_param bet_amount string
     * @return_param valid_bet_amount string
     * @return_param win_loss_amount string
     * @return_param bet_num string
     * @return_param valid_bet_num string
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     * @remark 擱置
     */
    public function winLossRecord(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 取得所有遊戲商
     * @description 取得所有遊戲商
     * @method get
     * @url /api/account/member/all-game-company/get
     * @return {"data": [{"id":"1","name":"DG"}]}
     * @return_param id int 遊戲商ID
     * @return_param name string 遊戲商名稱
     */
    public function allGameCompany(Request $request)
    {
        $res = GameCompany::get();

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
     * @catalog 帳號管理/會員相關API
     * @title 託售資訊
     * @description 託售資訊
     * @method get
     * @url /api/account/member/consignment-detail/get
     * @param id true int 會員ID
     * @return {"data":{"id":"1","bank_name":"台灣銀行","account_name":"王曉明","account":"123456789"}}
     * @return_param id int 銀行帳戶ID
     * @return_param bank_name string 銀行名稱
     * @return_param account_name string 戶名
     * @return_param account string 帳號
     * @remark 擱置
     */
    public function consignmentDetail(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 新增帳戶
     * @description 新增帳戶
     * @method post
     * @url /api/account/member/add-bank
     * @param bank_name true string 銀行名稱
     * @param account_name true string 戶名
     * @param account true string 帳號
     * @remark 擱置
     */
    public function addBank(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 驗證帳戶
     * @description 驗證帳戶
     * @method post
     * @url /api/account/member/verify-bank
     * @param id true int 會員ID
     * @remark 擱置
     */
    public function verifyBank(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 取得會員遊戲商設定
     * @description 取得會員的啟用遊戲商
     * @method get
     * @url /api/account/member/game-company-setting/get
     * @param id true int 會員ID
     * @return {"data":[{"id":"1","company_name":"DG","status":"1"}]}
     * @return_param id int 設定值ID
     * @return_param company_name string 遊戲商名稱
     * @return_param status int 狀態
     */
    public function gameCompanySetting(MemberGameCompanySettingRequest $request)
    {
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $res = MemberGameCompanyConfig::where('member_id', $request->id)
            ->get();

        if (!count($res)) {
            $insertData = [];
            GameCompany::get()->each(function ($item) use (&$insertData, $request) {
                array_push($insertData, [
                    'member_id' => $request->id,
                    'company_id' => $item->id,
                ]);
            });
            MemberGameCompanyConfig::insert($insertData);
            $res = MemberGameCompanyConfig::where('member_id', $request->id)
                ->get();
        }

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'company_name' => $row->company->name,
                    'status' => $row->status,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 啟停用會員遊戲商
     * @description 啟停用會員遊戲商
     * @method post
     * @url /api/account/member/game-company/toggle-enabled
     * @param member_id true int 會員ID
     * @param company_id true int 遊戲商ID
     * @param status true int 狀態
     */
    public function GameCompanyToggleEnabled(MemberGameCompanyToggleEnabledRequest $request)
    {
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $setting = MemberGameCompanyConfig::where('member_id', $request->member_id)
            ->where('company_id', $request->company_id)
            ->first();
        $setting->status = $request->status;
        $setting->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 取得會員遊戲設定
     * @description 取得會員遊戲設定
     * @method get
     * @url /api/account/member/game-setting/get
     * @param member_id true int 會員ID
     * @param company_id true int 遊戲商ID
     * @return {"data":[{"id":"1","game_name":"DG","status":"1"}]}
     * @return_param id int 遊戲ID
     * @return_param name string 遊戲名稱
     * @return_param status int 狀態
     */
    public function gameSetting(MemberGameSettingRequest $request)
    {
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $res = MemberGameConfig::where('member_id', $request->member_id)
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
                        'member_id' => $request->member_id,
                        'game_id' => $item->id,
                    ]);
                });
            MemberGameConfig::insert($insertData);
            $res = MemberGameConfig::where('member_id', $request->member_id)
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
                    'game_name' => $row->game->name,
                    'status' => $row->status,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 啟停用會員遊戲
     * @description 啟停用會員遊戲
     * @method post
     * @url /api/account/member/game/toggle-enabled
     * @param member_id true int 會員ID
     * @param game_id true int 遊戲ID
     * @param status true int 狀態
     */
    public function GameToggleEnabled(MemberGameToggleEnabledRequest $request)
    {
        /** @var User */
        $user = auth()->user();
        if ($user->isAgent()) {
            throw new ForbiddenException();
        }

        $setting = MemberGameConfig::where('member_id', $request->member_id)
            ->where('game_id', $request->game_id)
            ->first();
        $setting->status = $request->status;
        $setting->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 取得會員儲值方式
     * @description 取得會員儲值方式
     * @method post
     * @url /api/account/member/all-deposit-method
     * @param id true int 會員ID
     * @return {"data":{"convenience_store":"1","credit_card":"0","atm":"1","bank": [{"bank_name":"臺灣銀行","account":"44445555","line":"88888"}]}}
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
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 變更存款方式
     * @description 變更存款方式
     * @method post
     * @url /api/account/member/update-deposit-method
     * @param id true int 會員ID
     * @param convenience_stroe true int 超商
     * @param credit_card true int 信用卡
     * @param atm true int ATM
     * @param bank true array 銀行
     * @remark 擱置，bank欄位格式 [["bank_id":"1","status":"1"], ...]
     */
    public function updateDepositMethod(Request $request)
    {
        // TODO:
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 登入記錄
     * @description 登入記錄
     * @method get
     * @url /api/account/member/login-log/get
     * @param id true int 會員ID
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
        $query = MemberLoginLog::where('member_id', $request->id);
        $per_page = intval($request->per_page ?? 15);
        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) use ($request) {
                return [
                    'id' => $row->id,
                    'ip' => $row->ip,
                    'device' => $row->device,
                    'browser' => $row->browser,
                    'login_at' => $row->created_at,
                    'ip_exists' => $row->sameIpExists($row->ip, $request->id),
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 登入此IP使用者
     * @description 登入此IP使用者
     * @method get
     * @url /api/account/member/same-ip-user-login/get
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
        $res = (new LoginLogService())->sameIpUserLogin($request->ip);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 登入此IP會員
     * @description 登入此IP會員
     * @method get
     * @url /api/account/member/same-ip-member-login/get
     * @param id true int 會員ID
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
        $member = Member::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpMemberLogin($request->ip, $member->id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 註冊此IP會員
     * @description 註冊此IP會員
     * @method get
     * @url /api/account/member/same-ip-member-register/get
     * @param id true int 會員ID
     * @param ip true string IP
     * @return {"data":[{"id":"id","username":"member01","register_at":"2020-1-1 10:10:10"}]}
     * @return_param id int 會員ID
     * @return_param username string 帳號
     * @return_param register_at string 註冊時間
     */
    public function sameIpMemberRegister(Request $request)
    {
        $member = Member::findOrFail($request->id);
        $res = (new LoginLogService())->sameIpMemberRegister($request->ip, $member->id);
        return $this->returnData($res);
    }

    /**
     * showdoc
     * @catalog 帳號管理/會員相關API
     * @title 特定會員及IP登入記錄
     * @description 特定會員及IP登入記錄
     * @method get
     * @url /api/account/member/login-record-by-member-ip/get
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
     * @catalog 帳號管理/會員相關API
     * @title 特定帳號及IP登入記錄
     * @description 特定帳號及IP登入記錄
     * @method get
     * @url /api/account/member/login-record-by-user-ip/get
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
