<?php

namespace App\Http\Controllers\CustomerService;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerService\LineSettingRequest;
use App\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LineSettingController extends Controller
{
    protected $logChannel = 'linesettinglog';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user->isAgent() || ($user->isCustomerService() && $user->detail->level > 1)) {
                throw new ForbiddenException();
            }
            return $next($request);
        });
    }

    /**
     * showdoc
     * @catalog 客服/LINE設定相關API
     * @title 客服LINE列表
     * @description 客服LINE列表
     * @method get
     * @url /api/customer-service/line-setting
     * @return {"data":[{"id":"1","username":"cs_001","line_id":"564354654","line_qrcode":""}]}
     * @return_param id int 客服ID
     * @return_param username string 客服帳號
     * @return_param line_id string LineId
     * @return_param line_qrcode image LineQrcode
     */
    public function index(Request $request)
    {
        $res = CustomerService::where('level', 2)->get();

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'username' => $row->user->username,
                    'line_id' => $row->line_id,
                    'line_qrcode' => $row->line_qrcode ? Storage::url($row->line_qrcode) : null,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 客服/LINE設定相關API
     * @title 更新客服LINE
     * @description 更新客服LINE
     * @method patch
     * @url /api/customer-service/line-setting/{id}
     * @param id true int 客服ID
     * @param line_id false string LineId
     * @param line_qrcode false image LineQrcode
     */
    public function update(LineSettingRequest $request, $id)
    {
        $cs = CustomerService::findOrFail($id);
        $cs->line_id = $request->line_id;
        if ($request->hasFile('line_qrcode')) {
            $img = Storage::disk('storage-public')->put('upload/line-qrcode', $request->line_qrcode);
            $cs->line_qrcode = $img;
        }
        $cs->saveOrFail();
    }
}
