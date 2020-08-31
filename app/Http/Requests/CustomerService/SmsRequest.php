<?php

namespace App\Http\Requests\CustomerService;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
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
            'id' => 'required|exists:sms,id',
            'content' => 'required|string',
            'send_at' => 'required|date_format:"Y-m-d H:i:s"',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('sm');
        return $data;
    }
}
