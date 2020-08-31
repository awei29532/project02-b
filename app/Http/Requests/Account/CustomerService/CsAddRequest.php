<?php

namespace App\Http\Requests\Account\CustomerService;

use Illuminate\Foundation\Http\FormRequest;

class CsAddRequest extends FormRequest
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
            'password' => 'required|regex:/[a-z]/|min:6|max:10',
            'nickname' => 'nullable|string|max:16',
            'level' => 'required|in:1,2',
        ];
    }
}
