<?php

namespace App\Http\Controllers\Game;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameCompanyMaintenance\MaintenanceStoreRequest;
use App\Http\Requests\Game\GameCompanyMaintenance\MaintenanceUpdateRequest;
use App\Http\Requests\Game\GameCompanyMaintenance\CompanyChangeStatusRequest;
use App\Models\GameCompany;
use App\Models\GameCompanyMaintenance;
use Illuminate\Support\Arr;
use DB;

class GameCompanyController extends Controller
{
    protected $logChannel = 'gamesettinglog';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user->isAgent())
                throw new ForbiddenException();

            return $next($request);
        });
    }

    /**
     * showdoc
     * @catalog 遊戲管理/遊戲設定相關API
     * @title 取得所有遊戲商
     * @description 取得所有遊戲商
     * @method GET
     * @url /game/setting/maintain
     * @return {"data":[{"id":1,"name":"DG","status":"1","game_company_maintenance":[{"id":1,"status":"1","type":"2","start_at":"2020-03-06 08:38:54","end_at":"2020-03-06 08:31:54","remark":"remark"}]},{"id":2,"name":"ABC","status":"0","game_company_maintenance":[]}]}
     * @return_param id int 遊戲商ID
     * @return_param name string 遊戲商名稱
     * @return_param status string 遊戲商狀態(0=關閉/1=啟動)
     * @return_param game_company_maintenance json 維修資訊
     * @return_param id int 維修ID
     * @return_param status string 維修狀態(0=關閉/1=啟動)
     * @return_param type string 維修類型(1=定期維修,2=手動維修)
     * @return_param start_at string 維修開始時間
     * @return_param end_at string 修結束時間
     * @return_param remark string 備註
     */
    public function index()
    {
        return $this->returnData(GameCompany::with('GameCompanyMaintenance')->get());
    }

    /**
     * showdoc
     * @catalog 遊戲管理/遊戲設定相關API
     * @title 新增遊戲商維修訊息
     * @description 新增遊戲商維修訊息
     * @method POST
     * @url /game/setting/maintain
     * @json_param {"company_id":1,"data":[{"type":"1","remark":"備註","start_at":"3 4 * * 5","end_at":"5 4 * * 1","status": 1},{"type":"2","remark":"備註","start_at":"2020-03-03 03:03:03","end_at":"2020-04-04 04:04:04","status": 0}]}
     * @param company_id true int 廠商ID
     * @param data true json 傳入資料
     * @param type true string 維修類型(1=定期維修,2=手動維修)
     * @param remark false string 備註
     * @param start_at true string 維修開始時間
     * @param end_at true string 修結束時間
     * @param status true int 啟動狀態(0=關閉/1=啟動)
     * @remark type 為定期維修可參考 crontab 的方式記錄，目前定期維修沒有幾月幾日的設定，所以該參數必須為 \* ex: 1 2 \* \* 5 Reference: https://crontab.guru/#5_4_*_*_1
     */
    public function store(MaintenanceStoreRequest $request)
    {
        $input = $request->input('data');

        $companyId = $request->get('company_id');

        collect($input)->map(function ($item, $index) use ($companyId) {
            $data = array_merge($item, ['maintainable_id' => $companyId, 'maintainable_type' => GameCompany::class]);
            GameCompanyMaintenance::create($data);
        });
    }

    /**
     * showdoc
     * @catalog 遊戲管理/遊戲設定相關API
     * @title 取得遊戲商維修設定
     * @description 取得遊戲商維修設定
     * @method GET
     * @url /game/setting/maintain/{id}
     * @param id true int 遊戲商ID
     * @return {"data":[{"id":"1","status":"1","start_at":"* 1 * * 1","end_at":"* 2 * * 1","remark":"備註", "type": "1"}, {"id":"2","status":"1","start_at":"1010-10-10 10:10:10","end_at":"1010-10-10 11:11:11","remark":"備註", "type": "2"}]}
     * @return_param id int 維修ID
     * @return_param status int 維修狀態
     * @return_param start_at string 維修開始時間
     * @return_param end_at string 維修結束時間
     * @return_param remark string 維修備註
     * @return_param type string 維修類型(1=固定維修/2=手動維修)
     */
    public function show($id)
    {
        return $this->returnData(
            GameCompany::find($id)->GameCompanyMaintenance->makeHidden(['start_at', 'end_at'])->each(function ($item) {
                $item->setAppends(['start_format','end_format','weekly']);
            })
        );
    }

    /**
     * showdoc
     * @catalog 遊戲管理/遊戲設定相關API
     * @title 修改遊戲商維修訊息
     * @description 修改遊戲商維修訊息
     * @method PUT/PATCH
     * @url /game/setting/maintain/{id}
     * @json_param {"data":[{"id":1,"type":"1","remark":"備註","start_at":"3 4 * * 1","end_at":"5 4 * * 1","status": 1},{"type":"2","remark":"備註","start_at":"2020-03-03 03:03:03","end_at":"2020-04-04 04:04:04","status": 0}]}
     * @param id true int 廠商ID
     * @param data true json 傳入資料
     * @param id false string 維修ID
     * @param type true string 維修類型(1=定期維修/2=手動維修)
     * @param remark false string 備註
     * @param start_at true string 維修開始時間
     * @param end_at true string 維修結束時間
     * @param status true int 啟動狀態(0=關閉/1=啟動)
     * @remark type 為定期維修可參考 crontab 的方式記錄，目前定期維修沒有幾月幾日的設定，所以該參數必須為 \* ex: 1 2 \* \* 5 Reference: https://crontab.guru/#5_4_*_*_1
     */
    public function update(MaintenanceUpdateRequest $request, $id)
    {
        $input = $request->input('data') ?? [];
        $inputIds = Arr::pluck($input, 'id');
        $ids = GameCompany::find($id)->GameCompanyMaintenance->pluck('id')->diff($inputIds)->values();

        $gameCompanyMaintenance = new GameCompanyMaintenance();
        $gameCompanyMaintenance->setKeyName('maintainable_id')->destroy($ids);

        // 沒資料
        if(!$input)
            return response('', 200);

        collect($input)->map(function ($item) use ($id) {
            $data = array_merge($item, ['maintainable_id' => $id, 'maintainable_type' => GameCompany::class]);
            GameCompanyMaintenance::updateOrCreate([
                'maintainable_id' => $id,
                'id' => $item['id'] ?? ''
            ], $data);
        });
    }

    /**
     * showdoc
     * @catalog 遊戲管理/遊戲設定相關API
     * @title 變更遊戲商API開關
     * @description 變更遊戲商API開關
     * @method PUT/PATCH
     * @url /game/setting/company/change-status/{id}
     * @param id true int 廠商ID
     * @param status true int 狀態(0=關閉/1=啟動)
     */
    public function changeStatus(CompanyChangeStatusRequest $request, $id)
    {
        GameCompany::find($id)->update([
            'status' => $request->input('status')
        ]);
    }
}
