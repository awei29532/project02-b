<?php

namespace App\Http\Requests\Account\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberManualDeductRequest extends FormRequest
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
            'id' => 'required|exists:member,id',
            'amount' => 'required|numeric|min:0',
            'remark' => 'nullable|string',
        ];
    }
}
