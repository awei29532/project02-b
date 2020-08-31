<?php

namespace App\Http\Controllers\CustomerService;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Models\ComplaintLetter;
use Illuminate\Http\Request;

class ComplaintLetterController extends Controller
{
    protected $logChannel = 'complaintletterlog';

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
     * @catalog 客服/投訴信相關API
     * @title 投訴信列表
     * @description 投訴信列表
     * @method get
     * @url /api/customer-service/complaint-letter
     * @param username false string 會員帳號
     * @param status fasle int 狀態，0=未讀取，1=已讀取
     * @param start_at false string 開始時間
     * @param end_at false string 結束時間
     * @return {"data":[{"id":"1","member_id":"1","username":"member01","archive":"1","title":"WTF","content":"31321354","created_at":"1010-10-10 10:10:10"}],"page":"1","per_page":"15","last_page":"5","total":"150"}
     * @return_param id int ID
     * @return_param member_id int 會員ID
     * @return_param username string 會員帳號
     * @return_param archive int 會員帳號是否封存
     * @return_param title string 標題
     * @return_param content string 內文
     * @return_param created_at string 創建時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function index(Request $request)
    {
        $query = ComplaintLetter::select('*');

        $username = $request->username ?? '';
        if ($username) {
            $query->whereHas('member', function ($q) use ($username) {
                $q->where('username', 'like', "%$username%");
            });
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
                    'username' => $row->member->username,
                    'archive' => $row->member->deleted_at ? 1 : 0,
                    'title' => $row->title,
                    'content' => $row->content,
                    'status' => $row->status,
                    'created_at' => $row->created_at,
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 客服/投訴信相關API
     * @title 讀取信件
     * @description 讀取信件
     * @method patch
     * @url /api/customer-service/complaint-letter/{id}
     */
    public function update(Request $request, $id)
    {
        $letter = ComplaintLetter::findOrFail($id);
        $letter->status = '1';
        $letter->saveOrFail();
    }
}
