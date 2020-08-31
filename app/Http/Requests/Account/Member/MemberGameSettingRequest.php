<?php

namespace App\Http\Requests\Account\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberGameSettingRequest extends FormRequest
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
        return [
            'member_id' => 'required|exists:member,id',
            'company_id' => 'required|exists:game_company,id',
        ];
    }
}
