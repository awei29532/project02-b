<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameCompanyCommission\CommissionUpdateRequest;
use App\Models\GameCompany;
use App\Models\GameCompanyCommission;
use Illuminate\Support\Arr;

class GameCompanyCommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user->isAdmin())
                throw new ForbiddenException();

            return $next($request);
        });
    }
    /**
     * showdoc
     * @catalog 遊戲管理/API上繳金額百分比設定
     * @title 取得所有API上繳金額百分比設定
     * @description 取得所有API上繳金額百分比設定
     * @method GET
     * @url /game/setting/commission
     * @return {"data":[{"id":1,"name":"DG","status":"1","game_company_commission":[{"ratio_win":"5","ratio_lose":"2","status":"1"}]},{"id":2,"name":"ABC","status":"0","game_company_commission":[{"ratio_win":"1","ratio_lose":"2","status":"0"}]}]}
     * @return_param id int 遊戲商ID
     * @return_param name string 遊戲商名稱
     * @return_param status string 遊戲商狀態
     * @return_param game_company_commission json Commission資訊
     * @return_param status string 顯示狀態(0=關閉/1=顯示)
     * @return_param ratio_win string 獲利百分比
     * @return_param ratio_lost string 虧損百分比
     */
    public function index()
    {
        return $this->returnData(
            GameCompany::with('GameCompanyCommission')->get()
        );
    }

    /**
     * showdoc
     * @catalog 遊戲管理/API上繳金額百分比設定
     * @title 修改API上繳金額百分比設定
     * @description 修改API上繳金額百分比設定
     * @method PUT/PATCH
     * @url /game/setting/commission/update
     * @json_param {"data":[{"company_id":1,"ratio_win":1,"ratio_lose":1,"status": 1},{"company_id":2,"ratio_win":3,"ratio_lose":4,"status": 0}]}
     * @param data true json 傳入資料
     * @param company_id true int 廠商ID
     * @param ratio_win true int 獲利百分比
     * @param ratio_lose true int 虧損百分比
     * @param status true string 顯示狀態(0=關閉/1=顯示)
     */
    public function update(CommissionUpdateRequest $request, $id)
    {
        if ($id != 'update')
            throw new ForbiddenException();

        $input = $request->input('data');

        collect($input)->map(function ($item) {
            GameCompanyCommission::updateOrCreate(
                Arr::only($item, [
                    'company_id'
                ]),
                $item
            );
        });
    }
}
