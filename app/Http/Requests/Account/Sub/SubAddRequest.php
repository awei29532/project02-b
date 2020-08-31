<?php

namespace App\Http\Requests\Account\Sub;

use Illuminate\Foundation\Http\FormRequest;

class SubAddRequest extends FormRequest
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
            'username' => 'required|string|unique:user,username',
            'password' => 'required|regex:/[a-zA-Z0-9]/|min:6|max:10',
            'nickname' => 'nullable|string|max:16',
            // 'view_member_type' => 'required|in:0,1,2',
            // 'permission.*' => 'nullable|exists:api,id',
        ];
    }
}
