<?php

namespace App\Http\Requests\Account\CustomerService;

use Illuminate\Foundation\Http\FormRequest;

class CsEditRequest extends FormRequest
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
            'nickname' => 'nullable|string|max:16',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('customer_service');
        return $data;
    }
}
