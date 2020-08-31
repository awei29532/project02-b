<?php

namespace App\Http\Requests\Account\CustomerService;

use Illuminate\Foundation\Http\FormRequest;

class CsChangePasswordRequest extends FormRequest
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
            'id' => 'required|exists:customer_service,id',
            'password' => 'required|regex:/[a-z]/|min:6|max:10',
        ];
    }
}
