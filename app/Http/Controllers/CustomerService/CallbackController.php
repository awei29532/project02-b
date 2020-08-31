<?php

namespace App\Http\Controllers\CustomerService;

use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerService\CallbackUpdateRequest;
use App\Models\Callback;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    protected $logChannel = 'callbacklog';

    protected $user;

    function __construct()
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
     * @catalog 客服/回電相關API
     * @title 回電列表
     * @description 回電列表
     * @method get
     * @url /api/customer-service/callback
     * @param username false string 會員帳號
     * @param phone false string 電話號碼
     * @param status fasle int 狀態，0=未處理，1=成功，2=封鎖，all=全部
     * @param start_at false string 開始時間
     * @param end_at false string 結束時間
     * @return {"data":[{"id":"1","ip":"127.0.0.1","phone":"123456789","member_id":"1","username":"member01","archive":"1","created_at":"1010-10-10 10:10:10","remark":"","status":"1","cs_username":"cs001","callback_at":"2020-10-10 00:00:00"}],"page":"1","per_page":"15","last_page":"5","total":"150"}
     * @return_param id int ID
     * @return_param ip string IP
     * @return_param phone string 電話號碼
     * @return_param member_id int 會員ID
     * @return_param username string 會員帳號
     * @return_param archive int 會員帳號是否封存
     * @return_param created_ta string 創建時間
     * @return_param remark string 備註
     * @return_param status int 狀態，0=未處理，1=成功，2=封鎖
     * @return_param cs_username string 客服帳號
     * @return_param callback_at string 回電時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function index(Request $request)
    {
        $query = Callback::select('*');

        $username = $request->username ?? '';
        if ($username) {
            $query->whereHas('member', function ($q) use ($username) {
                $q->where('username', 'like', "%$username%");
            });
        }

        $phone = $request->phone ?? '';
        if ($phone) {
            $query->where('phone', 'like', "%$phone%");
        }

        $status = $request->status ?? 'all';
        if ($status != 'all') {
            $query->where('status', $status);
        }

        $start_at = $request->start_at ?? '';
        if ($start_at) {
            $query->where('created_at', '>=', $start_at);
        }

        $end_at = $request->end_at ?? '';
        if ($end_at) {
            $query->where('created_at', '<=', $end_at);
        }

        $per_page = $request->per_page ?? 15;
        $res = $query->paginate($per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'member_id' => $row->member_id,
                    'username' => $row->member->username ?? '',
                    'archive' => $row->member_id ? ($row->member->deleted_at ? 1 : 0) : '',
                    'phone' => $row->phone,
                    'ip' => $row->ip,
                    'status' => $row->status,
                    'remark' => $row->remark,
                    'cs_username' => $row->cs->user->username ?? '',
                    'callback_at' => $row->callback_at,
                    'created_at' => $row->created_at,
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 客服/回電相關API
     * @title 承接
     * @description 承接
     * @method patch
     * @url /api/customer-service/callback/{id}
     * @param status true int 1=完成，2=封鎖
     * @param remark false string 備註
     */
    public function update(CallbackUpdateRequest $request, $id)
    {
        $user = auth()->user();
        if (!$user->isCustomerService()) {
            throw new ForbiddenException();
        }

        $call = Callback::where('id', $id)->where('status', '0')->first();
        if (!$call) {
            throw new BadRequestException(trans('common.callback_exists'), $this->logChannel);
        }

        $call->status = $request->status;
        $call->cs_id = $user->detail->id;
        $call->callback_at = Carbon::now();
        $call->saveOrFail();
    }
}
