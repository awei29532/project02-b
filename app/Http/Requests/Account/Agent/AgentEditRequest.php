<?php

namespace App\Http\Requests\Account\Agent;

use Illuminate\Foundation\Http\FormRequest;

class AgentEditRequest extends FormRequest
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
        $id = $this->route('agent');
        $rules = [
            'id' => 'required|exists:agent,id',
            'nickname' => 'nullable|string',
            'cell_phone' => "nullable|string|unique:agent,cell_phone,$id",
            'remark' => 'nullable|string',
        ];

        return $rules;
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('agent');
        return $data;
    }
}
