<?php

namespace App\Http\Requests\Game\GameCompanyCommission;

use Illuminate\Foundation\Http\FormRequest;

class CommissionUpdateRequest extends FormRequest
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
            'data' => 'required',
            'data.*.company_id' => 'required|exists:game_company,id',
            'data.*.ratio_win' => 'required|numeric|gte:0|lte:100',
            'data.*.ratio_lose' => 'required|numeric|gte:0|lte:100',
            'data.*.status' => 'required|in:1,0',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'data.*.company_id' => '廠商',
            'data.*.type' => '維修',
            'data' => '資料',
            'data.*.remark' => '備註',
            'data.*.status' => '狀態'
        ];
    }
}
