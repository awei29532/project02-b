<?php

namespace App\Http\Requests\Game\GameCompanyMaintenance;

use App\Rules\DateTimeOrCrontabFormat;
use App\Rules\DateTimeOrCrontabRange;
use Illuminate\Foundation\Http\FormRequest;

class MaintenanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'id' => 'required|exists:game_company,id',
            // 'data' => 'required',
            'data.*.type' => 'nullable|in:1,2',
            // 'data.*.remark' => 'required',
            'data.*.status' => 'nullable|in:1,0',
            'data.*.start_at' => [
                'nullable',
                new DateTimeOrCrontabFormat()
            ],
            'data.*.end_at' => [
                'nullable',
                new DateTimeOrCrontabFormat(),
                new DateTimeOrCrontabRange($this->toArray())
            ]
        ];

        return $rules;
    }

    /**
     * 擴充路由參數 id 進入檢查
     *
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = $this->route('maintain');
        return $data;
    }
}
