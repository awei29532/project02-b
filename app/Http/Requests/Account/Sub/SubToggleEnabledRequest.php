<?php

namespace App\Http\Requests\Account\Sub;

use Illuminate\Foundation\Http\FormRequest;

class SubToggleEnabledRequest extends FormRequest
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
            'id' => 'required|exists:sub_account,id',
            'status' => 'required|in:0,1',
        ];
    }
}
