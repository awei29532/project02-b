<?php

namespace App\Http\Requests\Game\GameCompanyMaintenance;

use Illuminate\Foundation\Http\FormRequest;

class CompanyChangeStatusRequest extends FormRequest
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
            'status' => 'required|in:1,0',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id' => '廠商',
            'status' => '狀態'
        ];
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
        $data['id'] = $this->route('id');
        return $data;
    }
}
