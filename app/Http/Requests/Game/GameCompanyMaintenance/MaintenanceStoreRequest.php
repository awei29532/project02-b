<?php

namespace App\Http\Requests\Game\GameCompanyMaintenance;

use App\Rules\DateTimeOrCrontabFormat;
use App\Rules\DateTimeOrCrontabRange;
use Illuminate\Foundation\Http\FormRequest;

class MaintenanceStoreRequest extends FormRequest
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
            'company_id' => 'required|exists:game_company,id',
            'data' => 'required',
            'data.*.type' => 'required|in:1,2',
            // 'data.*.remark' => 'required',
            'data.*.status' => 'required|in:1,0',
            'data.*.start_at' => [
                'required',
                new DateTimeOrCrontabFormat()
            ],
            'data.*.end_at' => [
                'required',
                new DateTimeOrCrontabFormat(),
                new DateTimeOrCrontabRange($this->toArray())
            ]
        ];

        return $rules;
    }
}
