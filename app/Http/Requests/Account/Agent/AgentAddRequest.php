<?php

namespace App\Http\Requests\Account\Agent;

use Illuminate\Foundation\Http\FormRequest;

class AgentAddRequest extends FormRequest
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
        $rules = [
            'username' => 'required|unique:user,username|min:6|max:10',
            'password' => 'required|regex:/[a-z]/|min:6|max:10',
            'nickname' => 'nullable|string|max:16',
            'cell_phone' => 'nullable|string',
        ];

        $parent_id = request()->parent_id ?? '';
        if ($parent_id) {
            $rules['parent_id'] = 'exists:agent,id';
        }

        return $rules;
    }
}
