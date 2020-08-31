<?php

namespace App\Http\Requests\CustomerService;

use Illuminate\Foundation\Http\FormRequest;

class LineSettingRequest extends FormRequest
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
            'line_id' => 'nullable|string',
            'line_qrcode' => 'nullable|image',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('line_setting');
        return $data;
    }
}
